-- USERS
CREATE TABLE users (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(100)   NOT NULL,
  email             VARCHAR(150)   NOT NULL UNIQUE,
  password_hash     VARCHAR(255)   NOT NULL,
  password_salt     VARCHAR(100)   NOT NULL,
  account_status    ENUM('active','suspended','inactive') NOT NULL DEFAULT 'active',
  email_verified    BOOLEAN        NOT NULL DEFAULT FALSE,
  storage_quota     BIGINT         NOT NULL DEFAULT 1073741824,  -- 1GB default
  storage_used      BIGINT         NOT NULL DEFAULT 0,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login_at     DATETIME       NULL,
  last_active_at    DATETIME       NULL,
  two_factor_enabled BOOLEAN       NOT NULL DEFAULT FALSE,
  profile_image_path VARCHAR(255)  NULL,
  INDEX (email),
  INDEX (account_status)
) ENGINE=InnoDB;

-- USER GROUPS (for group-based sharing)
CREATE TABLE user_groups (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(100)   NOT NULL,
  description       TEXT           NULL,
  created_by        INT            NOT NULL,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_private        BOOLEAN        NOT NULL DEFAULT TRUE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- GROUP MEMBERSHIP
CREATE TABLE group_members (
  group_id          INT            NOT NULL,
  user_id           INT            NOT NULL,
  role              ENUM('member','admin','owner') NOT NULL DEFAULT 'member',
  joined_at         DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (group_id, user_id),
  FOREIGN KEY (group_id) REFERENCES user_groups(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- SESSIONS
CREATE TABLE sessions (
  id                VARCHAR(255)   PRIMARY KEY,
  user_id           INT            NOT NULL,
  ip_address        VARCHAR(45)    NOT NULL,
  user_agent        TEXT           NULL,
  device_info       VARCHAR(255)   NULL,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME       NOT NULL,
  last_activity     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_revoked        BOOLEAN        NOT NULL DEFAULT FALSE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (expires_at),
  INDEX (user_id, is_revoked)
) ENGINE=InnoDB;

-- FRIENDSHIPS
CREATE TABLE friendships (
  user_id           INT NOT NULL,
  friend_id         INT NOT NULL,
  status            ENUM('pending','accepted','blocked','declined') NOT NULL DEFAULT 'pending',
  requested_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  responded_at      DATETIME NULL,
  PRIMARY KEY (user_id, friend_id),
  FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (status)
) ENGINE=InnoDB;

-- FOLDERS
CREATE TABLE folders (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  name              VARCHAR(255)   NOT NULL,
  description       TEXT           NULL,
  parent_folder_id  INT NULL,
  color             VARCHAR(7)     NULL,
  is_system         BOOLEAN        NOT NULL DEFAULT FALSE,  -- For system folders like "Trash"
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)          REFERENCES users(id)       ON DELETE CASCADE,
  FOREIGN KEY (parent_folder_id) REFERENCES folders(id)     ON DELETE SET NULL,
  INDEX (user_id, parent_folder_id)
) ENGINE=InnoDB;

-- DOCUMENT CATEGORIES / TAGS
CREATE TABLE tags (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(100)   NOT NULL UNIQUE,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  usage_count       INT            NOT NULL DEFAULT 0,
  color             VARCHAR(7)     NULL     -- For UI display
) ENGINE=InnoDB;

-- DOCUMENTS
CREATE TABLE documents (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  owner_id          INT NOT NULL,
  folder_id         INT NULL,
  title             VARCHAR(255)   NOT NULL,
  description       TEXT           NULL,
  latest_version    INT NOT NULL DEFAULT 1,
  is_public         BOOLEAN        NOT NULL DEFAULT FALSE,
  is_deleted        BOOLEAN        NOT NULL DEFAULT FALSE,
  is_favorite       BOOLEAN        NOT NULL DEFAULT FALSE,
  expires_at        DATETIME       NULL,
  purge_at          DATETIME       NULL,    -- For automatic deletion
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  last_accessed_at  DATETIME       NULL,
  FOREIGN KEY (owner_id)   REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (folder_id)  REFERENCES folders(id) ON DELETE SET NULL,
  FULLTEXT INDEX (title, description),      -- For search functionality
  INDEX (owner_id),
  INDEX (is_public),
  INDEX (is_deleted),
  INDEX (expires_at),
  INDEX (is_favorite)
) ENGINE=InnoDB;

-- DOCUMENT TEMPLATES
CREATE TABLE document_templates (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  name              VARCHAR(255)   NOT NULL,
  description       TEXT           NULL,
  file_path         VARCHAR(255)   NOT NULL,
  file_type         VARCHAR(50)    NOT NULL,
  created_by        INT            NOT NULL,
  is_public         BOOLEAN        NOT NULL DEFAULT FALSE,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- DOCUMENT VERSIONS
CREATE TABLE document_versions (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  version_number    INT NOT NULL,
  file_name         VARCHAR(255)   NOT NULL,
  file_path         VARCHAR(255)   NOT NULL,
  file_type         VARCHAR(50),
  mime_type         VARCHAR(100)   NULL,    -- More specific than file_type
  file_size         BIGINT,
  file_hash         VARCHAR(64)    NULL,
  storage_provider  ENUM('local','s3','google','azure') NOT NULL DEFAULT 'local',
  uploaded_at       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  uploaded_by       INT NOT NULL,
  change_summary    TEXT           NULL,
  is_autosave       BOOLEAN        NOT NULL DEFAULT FALSE,
  FOREIGN KEY (document_id)  REFERENCES documents(id)     ON DELETE CASCADE,
  FOREIGN KEY (uploaded_by)  REFERENCES users(id)         ON DELETE SET NULL,
  UNIQUE (document_id, version_number),
  INDEX (document_id),
  INDEX (uploaded_at)
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

-- FAVORITES
CREATE TABLE favorites (
  user_id           INT NOT NULL,
  document_id       INT NOT NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, document_id),
  FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ACCESS LOGS
CREATE TABLE document_access_logs (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  user_id           INT NULL,
  version_id        INT NULL,
  action            ENUM('view','download','delete','update','restore','share','preview','print') NOT NULL,
  occurred_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_address        VARCHAR(45) NULL,
  user_agent        VARCHAR(255) NULL,
  country_code      VARCHAR(2)  NULL,
  device_type       VARCHAR(50) NULL,
  FOREIGN KEY (document_id) REFERENCES documents(id)           ON DELETE CASCADE,
  FOREIGN KEY (user_id)     REFERENCES users(id)               ON DELETE SET NULL,
  FOREIGN KEY (version_id)  REFERENCES document_versions(id)   ON DELETE SET NULL,
  INDEX (document_id, user_id),
  INDEX (occurred_at)
) ENGINE=InnoDB;

-- SHARING / PERMISSIONS
CREATE TABLE document_shares (
  document_id       INT NOT NULL,
  shared_with_user  INT NULL,              -- NULL for group shares
  shared_with_group INT NULL,              -- NULL for user shares
  permission        ENUM('view','comment','edit','owner') NOT NULL DEFAULT 'view',
  shared_by_user    INT NOT NULL,
  shared_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME NULL,
  access_count      INT NOT NULL DEFAULT 0,
  message           TEXT NULL,             -- Optional share message
  watermark_enabled BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (document_id, shared_with_user, shared_with_group), -- Composite key
  FOREIGN KEY (document_id)       REFERENCES documents(id) ON DELETE CASCADE,
  FOREIGN KEY (shared_with_user)  REFERENCES users(id)     ON DELETE CASCADE,
  FOREIGN KEY (shared_with_group) REFERENCES user_groups(id) ON DELETE CASCADE,
  FOREIGN KEY (shared_by_user)    REFERENCES users(id)     ON DELETE SET NULL,
  INDEX (expires_at),
  CHECK (shared_with_user IS NOT NULL OR shared_with_group IS NOT NULL) -- Ensure at least one is set
) ENGINE=InnoDB;

-- PUBLIC DOCUMENT SHARES
CREATE TABLE public_document_links (
  id                VARCHAR(64)    PRIMARY KEY,
  document_id       INT NOT NULL,
  created_by        INT NOT NULL,
  permission        ENUM('view','comment','edit') NOT NULL DEFAULT 'view',
  password_hash     VARCHAR(255)   NULL,
  max_uses          INT            NULL,
  use_count         INT            NOT NULL DEFAULT 0,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at        DATETIME       NULL,
  watermark_enabled BOOLEAN        NOT NULL DEFAULT FALSE,
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by)  REFERENCES users(id)     ON DELETE CASCADE,
  INDEX (expires_at)
) ENGINE=InnoDB;

-- COMMENTS on documents
CREATE TABLE document_comments (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  document_id       INT NOT NULL,
  version_id        INT NULL,
  user_id           INT NOT NULL,
  parent_comment_id INT NULL,
  content           TEXT NOT NULL,
  position_data     JSON NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NULL,
  is_deleted        BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (document_id)      REFERENCES documents(id)          ON DELETE CASCADE,
  FOREIGN KEY (version_id)       REFERENCES document_versions(id)  ON DELETE SET NULL,
  FOREIGN KEY (user_id)          REFERENCES users(id)              ON DELETE CASCADE,
  FOREIGN KEY (parent_comment_id) REFERENCES document_comments(id) ON DELETE SET NULL,
  INDEX (document_id, is_deleted)
) ENGINE=InnoDB;

-- NOTIFICATIONS
CREATE TABLE notifications (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  type              ENUM('share','comment','friend_request','version','mention','system','group_invite') NOT NULL,
  entity_type       VARCHAR(50) NOT NULL,
  entity_id         INT NOT NULL,
  sender_id         INT NULL,
  message           TEXT NULL,
  is_read           BOOLEAN NOT NULL DEFAULT FALSE,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  action_url        VARCHAR(255) NULL,    -- Deep link for the notification
  FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX (user_id, is_read),
  INDEX (created_at)
) ENGINE=InnoDB;

-- DEVICE PUSH TOKENS
CREATE TABLE user_devices (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  device_token      VARCHAR(255) NOT NULL,
  device_type       ENUM('ios','android','web','desktop') NOT NULL,
  device_name       VARCHAR(100) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_used_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_active         BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE (user_id, device_token),
  INDEX (device_token)
) ENGINE=InnoDB;

-- USER SETTINGS
CREATE TABLE user_settings (
  user_id           INT PRIMARY KEY,
  notification_preferences JSON NOT NULL,
  ui_preferences    JSON NULL,
  default_share_permission ENUM('view','comment','edit') NOT NULL DEFAULT 'view',
  language          VARCHAR(10) NOT NULL DEFAULT 'en-US',
  timezone          VARCHAR(50) NOT NULL DEFAULT 'UTC',
  download_format   VARCHAR(10) NULL,      -- Preferred download format (original, pdf, etc.)
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- AUDIT LOG
CREATE TABLE audit_logs (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NULL,
  action_type       VARCHAR(50) NOT NULL,
  entity_type       VARCHAR(50) NULL,
  entity_id         INT NULL,
  details           JSON NULL,
  ip_address        VARCHAR(45) NULL,
  user_agent        VARCHAR(255) NULL,
  location          VARCHAR(100) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX (user_id, action_type),
  INDEX (created_at)
) ENGINE=InnoDB;

-- WATERMARK TEMPLATES
CREATE TABLE watermark_templates (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL,
  name              VARCHAR(100) NOT NULL,
  text              VARCHAR(255) NULL,
  image_path        VARCHAR(255) NULL,
  position          ENUM('top-left','top-right','bottom-left','bottom-right','center') NOT NULL DEFAULT 'bottom-right',
  opacity           INT NOT NULL DEFAULT 50,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- FRIEND-BASED SHARING VIEW
CREATE VIEW friend_document_shares AS
SELECT d.id AS document_id,
       f.friend_id AS shared_with_user,
       NULL AS shared_with_group,
       'view' AS permission,
       d.owner_id AS shared_by_user,
       NOW() AS shared_at,
       NULL AS expires_at,
       0 AS access_count,
       NULL AS message,
       FALSE AS watermark_enabled
FROM documents d
JOIN friendships f
  ON f.user_id = d.owner_id
WHERE f.status = 'accepted'
  AND d.is_public = FALSE
  AND d.is_deleted = FALSE;

-- GROUP-BASED SHARING VIEW
CREATE VIEW group_document_shares AS
SELECT d.id AS document_id,
       NULL AS shared_with_user,
       gm.group_id AS shared_with_group,
       'view' AS permission,
       d.owner_id AS shared_by_user,
       NOW() AS shared_at,
       NULL AS expires_at,
       0 AS access_count,
       NULL AS message,
       FALSE AS watermark_enabled
FROM documents d
JOIN group_members gm ON gm.user_id = d.owner_id
WHERE d.is_public = FALSE
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
  
  -- Update document's latest version if this is newer
  UPDATE documents
  SET latest_version = NEW.version_number
  WHERE id = NEW.document_id AND latest_version < NEW.version_number;
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
  -- Update last accessed time
  UPDATE documents
  SET last_accessed_at = NOW()
  WHERE id = NEW.document_id;
  
  -- Update share access count for user shares
  IF NEW.action IN ('view', 'download', 'preview', 'print') AND NEW.user_id IS NOT NULL THEN
    UPDATE document_shares 
    SET access_count = access_count + 1
    WHERE document_id = NEW.document_id AND shared_with_user = NEW.user_id;
  END IF;
END//
DELIMITER ;

-- Update public link usage count
DELIMITER //
CREATE TRIGGER after_public_access
AFTER INSERT ON document_access_logs
FOR EACH ROW
BEGIN
  IF NEW.action IN ('view', 'download', 'preview', 'print') AND NEW.user_id IS NULL THEN
    -- This is a public access
    UPDATE public_document_links
    SET use_count = use_count + 1
    WHERE document_id = NEW.document_id;
  END IF;
END//
DELIMITER ;

-- Create system folders for new users
DELIMITER //
CREATE TRIGGER after_user_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
  -- Create root folder
  INSERT INTO folders (user_id, name, is_system, created_at, updated_at)
  VALUES (NEW.id, 'Root', TRUE, NOW(), NOW());
  
  -- Create trash folder
  INSERT INTO folders (user_id, name, is_system, created_at, updated_at)
  VALUES (NEW.id, 'Trash', TRUE, NOW(), NOW());
END//
DELIMITER ;

-- Update favorite status when adding to favorites
DELIMITER //
CREATE TRIGGER after_favorite_insert
AFTER INSERT ON favorites
FOR EACH ROW
BEGIN
  UPDATE documents
  SET is_favorite = TRUE
  WHERE id = NEW.document_id AND owner_id = NEW.user_id;
END//
DELIMITER ;

-- Update favorite status when removing from favorites
DELIMITER //
CREATE TRIGGER after_favorite_delete
AFTER DELETE ON favorites
FOR EACH ROW
BEGIN
  UPDATE documents
  SET is_favorite = FALSE
  WHERE id = OLD.document_id AND owner_id = OLD.user_id;
END//
DELIMITER ;

-- Create notification for document shares
DELIMITER //
CREATE TRIGGER after_document_share_insert
AFTER INSERT ON document_shares
FOR EACH ROW
BEGIN
  IF NEW.shared_with_user IS NOT NULL THEN
    INSERT INTO notifications (user_id, type, entity_type, entity_id, sender_id, created_at)
    VALUES (NEW.shared_with_user, 'share', 'documents', NEW.document_id, NEW.shared_by_user, NOW());
  END IF;
END//
DELIMITER ;

-- Create notification for group invites
DELIMITER //
CREATE TRIGGER after_group_member_insert
AFTER INSERT ON group_members
FOR EACH ROW
BEGIN
  INSERT INTO notifications (user_id, type, entity_type, entity_id, sender_id, created_at)
  VALUES (NEW.user_id, 'group_invite', 'user_groups', NEW.group_id, 
          (SELECT created_by FROM user_groups WHERE id = NEW.group_id), NOW());
END//
DELIMITER ;