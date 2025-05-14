<?php
// Include the session start and middleware
session_start();
require_once __DIR__ . '/../middleware/Authenticate.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../model/User.php';
include_once __DIR__ . '/../model/Document.php';

// If you want to check if the user is authenticated
Authenticate::checkAuth(); // This will redirect to login if not authenticated

$userId = $_SESSION['user_id'] ?? null;

$userData = null;

if ($userId) {
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->id = $userId;

    $stmt = $user->read(); // This should get user by ID
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $userData = $row;
    }
}


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


<body class="bg-gray-50 font-sans" data-theme="mytheme">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <section class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 shadow-sm overflow-y-auto">
            <div class="flex items-center p-5 border-b border-gray-100">
                <i class="fas fa-file-alt text-2xl text-primary mr-3"></i>
                <h1 class="text-lg font-bold text-gray-800">DocShare</h1>
            </div>
            <ul class="py-4">
                <li>
                    <a href="dashboard.php" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-transparent ">
                        <i class="fas fa-th-large w-5 text-center mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="documents.php" class="flex items-center px-5 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary border-l-4 border-primary bg-blue-50 text-primary">
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
        </section>

       
          <!-- Main Content -->
        <div class="ml-64 flex-1 p-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Documents</h2>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" placeholder="Search documents..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 w-64 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div class="relative mr-4 cursor-pointer">
                        <i class="fas fa-bell text-gray-600"></i>
                        <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </div>
                      <div class="flex items-center cursor-pointer border border-gray-200 rounded-lg p-2 "  data-bs-toggle="modal" data-bs-target="#profileModal">
                          <!-- Button trigger modal -->

                        
                        <div>
    <span class="block text-sm font-medium"><?php echo htmlspecialchars($userData['name']); ?></span>
    <span class="block text-xs text-gray-500"><?php echo htmlspecialchars($userData['email']); ?></span>
</div>

                    </div>
                </div>
            </div>
            
           

            <!--- Recent docs-->
            <section class="bg-white rounded-xl shadow-md p-6 mb-8">
  <div class="flex justify-between items-center mb-5">
    <h3 class="text-lg font-semibold text-gray-800">Recent Documents</h3>
    <div class="flex gap-2">
     
      <button class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
        Filter
      </button>
       <button class="px-4 py-2 text-sm font-medium text-primary  rounded ">
        Upload
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
            <div class="w-9 h-9 bg-blue-100 text--600 rounded flex items-center justify-center">
              <i class="fas fa-file-alt"></i>
            </div>
            <div>
              <span class="block font-medium">Project Plan.pdf</span>
              <span class="text-xs text-gray-500">Apr 14, 2025</span>
            </div>
          </td>
          <td class="px-4 py-3">
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text--600">
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

</div>
    
  

<!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>     
   <script src="https://cdn.tailwindcss.com"></script>



   <script>



   </script>
  
</body>
</html>