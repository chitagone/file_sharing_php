<?php
// Include the session start and middleware
session_start();
require_once __DIR__ . '/../middleware/Authenticate.php';

// If you want to check if the user is authenticated
Authenticate::checkAuth(); // This will redirect to login if not authenticated


// Login logic below...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocShare - File Sharing Dashboard</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Add Bootstrap CDN -->

</head>
<body>


<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 shadow-sm overflow-y-auto">
            <div class="flex items-center p-5 border-b border-gray-100">
                <i class="fas fa-file-alt text-2xl text-primary mr-3"></i>
                <h1 class="text-lg font-bold text-gray-800">DocShare</h1>
            </div>
            <ul class="py-4">
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-primary bg-blue-50 text-primary">
                        <i class="fas fa-th-large w-5 text-center mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-file-alt w-5 text-center mr-3"></i>
                        <span>Documents</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-share-alt w-5 text-center mr-3"></i>
                        <span>Shared</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-star w-5 text-center mr-3"></i>
                        <span>Favorites</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-chart-line w-5 text-center mr-3"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-trash w-5 text-center mr-3"></i>
                        <span>Trash</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent">
                        <i class="fas fa-cog w-5 text-center mr-3"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="ml-64 flex-1 p-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" placeholder="Search documents..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 w-64 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div class="relative mr-4 cursor-pointer">
                        <i class="fas fa-bell text-gray-600"></i>
                        <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </div>
                    <div class="flex items-center cursor-pointer">
                          <!-- Button trigger modal -->
  <button type="button"  data-bs-toggle="modal" data-bs-target="#profileModal">
    <img src="https://picsum.photos/40/40" alt="User" class="rounded-full w-9 h-9 mr-2">

  </button>
                        
                        <div>
                            <span class="block text-sm font-medium">John Doe</span>
                            <span class="block text-xs text-gray-500">Admin</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-primary mb-4">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div class="text-sm text-gray-500 mb-1">Total Documents</div>
                    <div class="text-2xl font-bold">142</div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-secondary mb-4">
                        <i class="fas fa-share-alt text-xl"></i>
                    </div>
                    <div class="text-sm text-gray-500 mb-1">Shared Documents</div>
                    <div class="text-2xl font-bold">38</div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-success mb-4">
                        <i class="fas fa-eye text-xl"></i>
                    </div>
                    <div class="text-sm text-gray-500 mb-1">Views This Month</div>
                    <div class="text-2xl font-bold">1,247</div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center text-warning mb-4">
                        <i class="fas fa-download text-xl"></i>
                    </div>
                    <div class="text-sm text-gray-500 mb-1">Downloads</div>
                    <div class="text-2xl font-bold">92</div>
                </div>
            </div>

            <section class="bg-white rounded-xl shadow-md p-6 mb-8">
  <div class="flex justify-between items-center mb-5">
    <h3 class="text-lg font-semibold text-gray-800">Recent Documents</h3>
    <div class="flex gap-2">
      <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
        Upload
      </button>
      <button class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
        Filter
      </button>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-left text-sm">
      <thead class="bg-blue-50 text-gray-700 font-semibold">
        <tr>
          <th class="px-4 py-3">Document Name</th>
          <th class="px-4 py-3">Category</th>
          <th class="px-4 py-3">Shared</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b hover:bg-blue-50">
          <td class="px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
              <i class="fas fa-file-alt"></i>
            </div>
            <div>
              <span class="block font-medium">Project Plan.pdf</span>
              <span class="text-xs text-gray-500">Apr 14, 2025</span>
            </div>
          </td>
          <td class="px-4 py-3">
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-600">
              Marketing
            </span>
          </td>
          <td class="px-4 py-3 text-gray-500">
            <i class="fas fa-user-friends mr-1"></i> 3 users
          </td>
          <td class="px-4 py-3 flex gap-2">
            <button class="w-8 h-8 rounded bg-gray-100 hover:bg-blue-600 hover:text-white flex items-center justify-center">
  <i class="fas fa-share-alt"></i>
</button>

            <button class="w-8 h-8 rounded bg-gray-100 hover:bg-blue-600 hover:text-white flex items-center justify-center">
              <i class="fas fa-download"></i>
            </button>
            <button class="w-8 h-8 rounded bg-gray-100 hover:bg-red-600 hover:text-white flex items-center justify-center">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        </tr>

        <!-- Repeat rows as needed -->
      </tbody>
    </table>
  </div>
</section>


            

<!-- modal user profile--->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="profileModalLabel">User Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <!-- User profile image -->
           <div class=" item-center">
 <img src="https://picsum.photos/100/100" alt="User" class="rounded-circle w-32 h-32 mb-2">
           </div>
         
          <h5 class="font-weight-bold">John Doe</h5>
          <p class="text-muted">john.doe@example.com</p>
        </div>
        <!-- User information -->
        <div>
          <h6 class="font-weight-bold">About Me</h6>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         <a href="logout.php" class="btn btn-primary">Logout</a>
      </div>
    </div>
  </div>
</div>
            
  

<!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>     
   <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        // Share Modal Functions
        const shareModal = document.getElementById('shareModal');
        const shareDocName = document.getElementById('shareDocName');


          document.getElementById('logoutBtn').addEventListener('click', function () {
            // Make a request to logout.php when the button is clicked
            window.location.href = '/document_api/public/login.php';
        });
        
        function openShareModal(docName) {
            shareModal.classList.add('active');
            shareDocName.textContent = `Share "${docName}"`;
        }
        
        function closeShareModal() {
            shareModal.classList.remove('active');
        }
        
        // Close modal when clicking on X or outside the modal
        document.querySelectorAll('.close-modal').forEach(close => {
            close.addEventListener('click', function() {
                this.closest('.modal').classList.remove('active');
            });
        });
        
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        });
        
        // Upload Modal Functions
        const uploadModal = document.getElementById('uploadModal');
        const uploadBtn = document.getElementById('uploadBtn');
        
        uploadBtn.addEventListener('click', function() {
            uploadModal.classList.add('active');
        });
        
        function closeUploadModal() {
            uploadModal.classList.remove('active');
        }
        
        // Sample data for demonstration
        // In a real application, this would come from your backend
        const users = [
            { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin' },
            { id: 2, name: 'Sarah Johnson', email: 'sarah@example.com', role: 'Editor' },
            { id: 3, name: 'Mike Richards', email: 'mike@example.com', role: 'Viewer' }
        ];
        
        const documents = [
            { 
                id: 1, 
                name: 'Annual Report 2024', 
                type: 'PDF', 
                size: '4.5 MB', 
                category: 'Finance',
                sharedWith: 5,
                modified: 'May 10, 2025'
            },
            { 
                id: 2, 
                name: 'Project Proposal', 
                type: 'DOCX', 
                size: '2.1 MB', 
                category: 'Marketing',
                sharedWith: 3,
                modified: 'May 8, 2025'
            },
            { 
                id: 3, 
                name: 'Budget Analysis Q2 2025', 
                type: 'XLSX', 
                size: '1.8 MB', 
                category: 'Finance',
                sharedWith: 2,
                modified: 'May 5, 2025'
            },
            { 
                id: 4, 
                name: 'Partnership Agreement', 
                type: 'PDF', 
                size: '3.2 MB', 
                category: 'Legal',
                sharedWith: 7,
                modified: 'May 3, 2025'
            },
            { 
                id: 5, 
                name: 'Q1 Results Presentation', 
                type: 'PPTX', 
                size: '6.7 MB', 
                category: 'General',
                sharedWith: 12,
                modified: 'April 28, 2025'
            }
        ];
        
        // This function would be expanded in a real application
        function searchDocuments(query) {
            if (!query) return documents;
            
            query = query.toLowerCase();
            return documents.filter(doc => 
                doc.name.toLowerCase().includes(query) || 
                doc.category.toLowerCase().includes(query)
            );
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-bar input');
        searchInput.addEventListener('input', function() {
            const query = this.value;
            const results = searchDocuments(query);
            // In a real app, you would update the table with the results
            console.log("Search results:", results);
        });
    </script>
</body>
</html>