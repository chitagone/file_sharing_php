-- USERS
CREATE TABLE users (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(100)   NOT NULL,
  email             VARCHAR(150)   NOT NULL UNIQUE,
  password_hash     VARCHAR(255)   NOT NULL,
  password_salt     VARCHAR(100)   NOT NULL,                     -- Added for better security
  account_status    ENUM('active','suspended','inactive') NOT NULL DEFAULT 'active', -- Account status tracking
  email_verified    BOOLEAN        NOT NULL DEFAULT FALSE,       -- Email verification status
  storage_quota     BIGINT         NOT NULL DEFAULT 1073741824,  -- User storage quota (default 1GB)
  storage_used      BIGINT         NOT NULL DEFAULT 0,           -- Track storage usage
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login_at     DATETIME       NULL,
  last_active_at    DATETIME       NULL,                         -- Track user activity
  INDEX (email),
  INDEX (account_status)                                         -- Index for account status queries
) ENGINE=InnoDB;

-- SESSIONS (for tracking active logins)
CREATE TABLE sessions (
  id                VARCHAR(255)   PRIMARY KEY,                  -- Session token
  user_id           INT            NOT NULL,
  ip_address        VARCHAR(45)    NOT NULL,                     -- Support both IPv4 and IPv6
  user_agent        TEXT           NULL,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME       NOT NULL,
  last_activity     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (expires_at)                                             -- For session cleanup
) ENGINE=InnoDB;

-- FRIENDSHIPS (mutual approval)
CREATE TABLE friendships (
  user_id           INT NOT NULL,
  friend_id         INT NOT NULL,
  status            ENUM('pending','accepted','blocked','declined') NOT NULL DEFAULT 'pending', -- Added declined status
  requested_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  responded_at      DATETIME NULL,
  PRIMARY KEY (user_id, friend_id),
  FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (status)                                                 -- Index for status filtering
) ENGINE=InnoDB;

-- FOLDERS (optional organization)
CREATE TABLE folders (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  name              VARCHAR(255)   NOT NULL,
  description       TEXT           NULL,                         -- Optional description
  parent_folder_id  INT NULL,
  color             VARCHAR(7)     NULL,                         -- For UI customization
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)          REFERENCES users(id)       ON DELETE CASCADE,
  FOREIGN KEY (parent_folder_id) REFERENCES folders(id)     ON DELETE SET NULL,
  INDEX (user_id, parent_folder_id)                               -- Optimize folder hierarchy queries
) ENGINE=InnoDB;

-- DOCUMENT CATEGORIES / TAGS
CREATE TABLE tags (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(100)   NOT NULL UNIQUE,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  usage_count       INT            NOT NULL DEFAULT 0            -- Track popularity for suggestions
) ENGINE=InnoDB;

-- DOCUMENTS
CREATE TABLE documents (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  owner_id          INT NOT NULL,
  folder_id         INT NULL,
  title             VARCHAR(255)   NOT NULL,
  description       TEXT           NULL,                         -- Document description
  latest_version    INT NOT NULL DEFAULT 1,
  is_public         BOOLEAN        NOT NULL DEFAULT FALSE,
  is_deleted        BOOLEAN        NOT NULL DEFAULT FALSE,       -- Soft delete support
  expires_at        DATETIME       NULL,                         -- Document expiration date
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  last_accessed_at  DATETIME       NULL,                         -- Track document usage
  FOREIGN KEY (owner_id)   REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (folder_id)  REFERENCES folders(id) ON DELETE SET NULL,
  INDEX (owner_id),
  INDEX (is_public),
  INDEX (is_deleted),
  INDEX (expires_at)                                             -- For expired document cleanup
) ENGINE=InnoDB;

-- DOCUMENT VERSIONS (for audit / rollback)
CREATE TABLE document_versions (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  version_number    INT NOT NULL,
  file_name         VARCHAR(255)   NOT NULL,
  file_path         VARCHAR(255)   NOT NULL,
  file_type         VARCHAR(50),
  file_size         BIGINT,                                      -- Changed to BIGINT for larger files
  file_hash         VARCHAR(64)    NULL,                         -- Store hash for integrity checks
  uploaded_at       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  uploaded_by       INT NOT NULL,
  change_summary    TEXT           NULL,                         -- Description of changes
  FOREIGN KEY (document_id)  REFERENCES documents(id)     ON DELETE CASCADE,
  FOREIGN KEY (uploaded_by)  REFERENCES users(id)         ON DELETE SET NULL,
  UNIQUE (document_id, version_number),
  INDEX (document_id)                                            -- Optimize version queries
) ENGINE=InnoDB;

-- DOCUMENT TAGS
CREATE TABLE document_tags (
  document_id       INT NOT NULL,
  tag_id            INT NOT NULL,
  added_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  added_by          INT NOT NULL,
  PRIMARY KEY (document_id, tag_id),
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id)      REFERENCES tags(id)      ON DELETE CASCADE,
  FOREIGN KEY (added_by)    REFERENCES users(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- ACCESS LOGS
CREATE TABLE document_access_logs (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  user_id           INT NULL,                                    -- Nullable for anonymous access
  version_id        INT NULL,
  action            ENUM('view','download','delete','update','restore','share') NOT NULL,
  occurred_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_address        VARCHAR(45) NULL,                            -- Track IP for security
  user_agent        VARCHAR(255) NULL,                           -- Track device/browser
  FOREIGN KEY (document_id) REFERENCES documents(id)           ON DELETE CASCADE,
  FOREIGN KEY (user_id)     REFERENCES users(id)               ON DELETE SET NULL,
  FOREIGN KEY (version_id)  REFERENCES document_versions(id)   ON DELETE SET NULL,
  INDEX (document_id, user_id),                                  -- Combined index for query performance
  INDEX (occurred_at)                                            -- Time-based queries
) ENGINE=InnoDB;

-- SHARING / PERMISSIONS
CREATE TABLE document_shares (
  document_id       INT NOT NULL,
  shared_with_user  INT NOT NULL,
  permission        ENUM('view','comment','edit','owner') NOT NULL DEFAULT 'view',
  shared_by_user    INT NOT NULL,
  shared_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME NULL,                               -- When the share expires
  access_count      INT NOT NULL DEFAULT 0,                       -- Count number of accesses
  PRIMARY KEY (document_id, shared_with_user),
  FOREIGN KEY (document_id)       REFERENCES documents(id) ON DELETE CASCADE,
  FOREIGN KEY (shared_with_user)  REFERENCES users(id)     ON DELETE CASCADE,
  FOREIGN KEY (shared_by_user)    REFERENCES users(id)     ON DELETE SET NULL,
  INDEX (expires_at)                                             -- For expired share cleanup
) ENGINE=InnoDB;

-- PUBLIC DOCUMENT SHARES (for sharing via link)
CREATE TABLE public_document_links (
  id                VARCHAR(64)    PRIMARY KEY,                  -- Unique token for public link
  document_id       INT NOT NULL,
  created_by        INT NOT NULL,
  permission        ENUM('view','comment','edit') NOT NULL DEFAULT 'view',
  password_hash     VARCHAR(255)   NULL,                         -- Optional password protection
  max_uses          INT            NULL,                         -- Limit number of accesses
  use_count         INT            NOT NULL DEFAULT 0,           -- Track usage
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME       NULL,                         -- Link expiration
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by)  REFERENCES users(id)     ON DELETE CASCADE,
  INDEX (expires_at)
) ENGINE=InnoDB;

-- COMMENTS on documents
CREATE TABLE document_comments (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  version_id        INT NULL,                                    -- May refer to specific version
  user_id           INT NOT NULL,
  parent_comment_id INT NULL,                                    -- For threaded comments
  content           TEXT NOT NULL,
  position_data     JSON NULL,                                   -- For positional comments (e.g., on specific text)
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NULL,
  is_deleted        BOOLEAN NOT NULL DEFAULT FALSE,              -- Soft delete support
  FOREIGN KEY (document_id)      REFERENCES documents(id)          ON DELETE CASCADE,
  FOREIGN KEY (version_id)       REFERENCES document_versions(id)  ON DELETE SET NULL,
  FOREIGN KEY (user_id)          REFERENCES users(id)              ON DELETE CASCADE,
  FOREIGN KEY (parent_comment_id) REFERENCES document_comments(id) ON DELETE SET NULL,
  INDEX (document_id, is_deleted)                                  -- Common filter combination
) ENGINE=InnoDB;

-- NOTIFICATIONS with specific entity types
CREATE TABLE notifications (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  type              ENUM('share','comment','friend_request','version','mention','system') NOT NULL,
  entity_type       VARCHAR(50) NOT NULL,                        -- Type of entity (documents, friendships, etc.)
  entity_id         INT NOT NULL,                                -- ID in the entity table
  sender_id         INT NULL,                                    -- Who triggered the notification
  message           TEXT NULL,                                   -- Custom notification text
  is_read           BOOLEAN NOT NULL DEFAULT FALSE,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX (user_id, is_read)                                       -- For unread notifications
) ENGINE=InnoDB;

-- DEVICE PUSH TOKENS (for mobile notifications)
CREATE TABLE user_devices (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  device_token      VARCHAR(255) NOT NULL,
  device_type       ENUM('ios','android','web') NOT NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_used_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE (user_id, device_token),
  INDEX (device_token)
) ENGINE=InnoDB;

-- USER SETTINGS (preferences, notifications, etc.)
CREATE TABLE user_settings (
  user_id           INT PRIMARY KEY,
  notification_preferences JSON NOT NULL,                      -- Store notification settings as JSON
  ui_preferences    JSON NULL,                                 -- UI customization settings
  default_share_permission ENUM('view','comment','edit') NOT NULL DEFAULT 'view',
  language          VARCHAR(10) NOT NULL DEFAULT 'en-US',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- AUDIT LOG (for security-relevant actions)
CREATE TABLE audit_logs (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NULL,                                  -- Null for system actions
  action_type       VARCHAR(50) NOT NULL,                      -- login, logout, settings change, etc.
  entity_type       VARCHAR(50) NULL,                          -- Type of entity affected
  entity_id         INT NULL,                                  -- ID in the entity table
  details           JSON NULL,                                 -- Additional details about the action
  ip_address        VARCHAR(45) NULL,
  user_agent        VARCHAR(255) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX (user_id, action_type),
  INDEX (created_at)
) ENGINE=InnoDB;

-- FRIEND-BASED SHARING VIEW
CREATE VIEW friend_document_shares AS
SELECT d.id AS document_id,
       f.friend_id AS shared_with_user,
       'view'        AS permission,
       d.owner_id    AS shared_by_user,
       NOW()         AS shared_at,
       NULL          AS expires_at,
       0             AS access_count
FROM documents d
JOIN friendships f
  ON f.user_id = d.owner_id
WHERE f.status = 'accepted'
  AND d.is_public = FALSE
  AND d.is_deleted = FALSE;

-- TRIGGERS

-- Update storage_used when adding document versions
DELIMITER //
CREATE TRIGGER after_document_version_insert
AFTER INSERT ON document_versions
FOR EACH ROW
BEGIN
  UPDATE users 
  SET storage_used = storage_used + NEW.file_size
  WHERE id = (SELECT owner_id FROM documents WHERE id = NEW.document_id);
END//
DELIMITER ;

-- Update storage_used when deleting document versions
DELIMITER //
CREATE TRIGGER after_document_version_delete
AFTER DELETE ON document_versions
FOR EACH ROW
BEGIN
  UPDATE users 
  SET storage_used = storage_used - OLD.file_size
  WHERE id = (SELECT owner_id FROM documents WHERE id = OLD.document_id);
END//
DELIMITER ;

-- Update tag usage count when adding tags
DELIMITER //
CREATE TRIGGER after_document_tag_insert
AFTER INSERT ON document_tags
FOR EACH ROW
BEGIN
  UPDATE tags SET usage_count = usage_count + 1 WHERE id = NEW.tag_id;
END//
DELIMITER ;

-- Update tag usage count when removing tags
DELIMITER //
CREATE TRIGGER after_document_tag_delete
AFTER DELETE ON document_tags
FOR EACH ROW
BEGIN
  UPDATE tags SET usage_count = usage_count - 1 WHERE id = OLD.tag_id;
END//
DELIMITER ;

-- Increment access count when shared document is accessed
DELIMITER //
CREATE TRIGGER after_access_log_insert
AFTER INSERT ON document_access_logs
FOR EACH ROW
BEGIN
  IF NEW.action = 'view' AND NEW.user_id IS NOT NULL THEN
    UPDATE document_shares 
    SET access_count = access_count + 1
    WHERE document_id = NEW.document_id AND shared_with_user = NEW.user_id;
    
    UPDATE documents
    SET last_accessed_at = NOW()
    WHERE id = NEW.document_id;
  END IF;
END//
DELIMITER ;

-- Update public link usage count
DELIMITER //
CREATE TRIGGER after_public_access
AFTER INSERT ON document_access_logs
FOR EACH ROW
BEGIN
  IF NEW.action = 'view' AND NEW.user_id IS NULL THEN
    -- This is a public access
    UPDATE public_document_links
    SET use_count = use_count + 1
    WHERE document_id = NEW.document_id;
  END IF;
END//
DELIMITER ;