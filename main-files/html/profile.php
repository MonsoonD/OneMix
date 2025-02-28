<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$dsn = 'mysql:dbname=onemix;host=127.0.0.1';
$user = 'root';
$password = '';
$pdo = new PDO($dsn, $user, $password);

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_image']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($filetype, $allowed)) {
            $newFilename = uniqid() . '.' . $filetype;
            $uploadPath = 'uploads/' . $newFilename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                
                $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$newFilename, $userId]);
               
                $_SESSION['user']['profile_image'] = $newFilename;
            }
        }
    }
    
    $profile = $_POST['profile'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    
    $sql = "UPDATE users SET profile = ?, first_name = ?, last_name = ?, email = ?";
    $params = [$profile, $firstName, $lastName, $email];
    
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
        $params[] = $hashedPassword;
    }

    $sql .= " WHERE id = ?";
    $params[] = $userId;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $_SESSION['user'] = [
        'id' => $userId,
        'profile' => $profile,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'profile_image' => $_SESSION['user']['profile_image'] ?? null
    ];
    
    header('Location: profile.php?success=1');
    exit();
}// Get current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" data-x="html" data-x-toggle="html-overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/vendors.css">
    <link rel="stylesheet" href="css/main.css">

    <title>Edit Profile - MixOne</title>
</head>
    <body>
        <div class="header-margin"></div>
    
    <section class="layout-pt-lg layout-pb-lg bg-blue-2">
        <div class="container">
            <div class="row justify-center">
                <div class="col-xl-6 col-lg-7 col-md-9">
                    <div class="px-50 py-50 sm:px-20 sm:py-20 bg-white shadow-4 rounded-4">
                        <h1 class="text-22 fw-500">Vous voulez modifier votre profil ?</h1>
                        
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success mt-20">
                                Profil modifié avec succès !
                            </div>
                        <?php endif; ?>

                        <form action="profile.php" method="POST" class="row y-gap-20 pt-20">
                            <div class="col-12">
                                <div class="form-input">
                                    <select name="profile" required>
                                        <option value="artist" <?php echo $userData['profile'] === 'artist' ? 'selected' : ''; ?>>Artist</option>
                                        <option value="studio" <?php echo $userData['profile'] === 'studio' ? 'selected' : ''; ?>>Studio</option>
                                    </select>
                                    <label class="lh-1 text-14 text-light-1"></label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-input">
                                    <input type="text" name="first_name" required value="<?php echo htmlspecialchars($userData['first_name']); ?>">
                                    <label class="lh-1 text-14 text-light-1">Prénom</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-input">
                                    <input type="text" name="last_name" required value="<?php echo htmlspecialchars($userData['last_name']); ?>">
                                    <label class="lh-1 text-14 text-light-1">Nom</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-input">
                                    <input type="email" name="email" required value="<?php echo htmlspecialchars($userData['email']); ?>">
                                    <label class="lh-1 text-14 text-light-1">Email</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-input">
                                    <input type="password" name="new_password">
                                    <label class="lh-1 text-14 text-light-1">Nouveau Mot de passe</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mt-30">
                                    <div class="fw-500">Profile Image</div>
                                    <div class="row x-gap-20 y-gap-20 pt-15">
                                        <div class="col-auto">
                                            <div class="w-200">
                                                <div class="d-flex ratio ratio-1:1">
                                                    <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display: none;">
                                                    <label for="profileImage" class="flex-center flex-column text-center bg-blue-2 h-full w-1/1 absolute rounded-4 border-type-1 cursor-pointer">
                                                        <?php if (!empty($userData['profile_image'])): ?>
                                                            <img src="uploads/<?php echo htmlspecialchars($userData['profile_image']); ?>" alt="Current profile" class="size-100 rounded-22 object-cover">
                                                        <?php else: ?>
                                                            <div class="icon-upload-file text-40 text-blue-1 mb-10"></div>
                                                            <div class="text-blue-1 fw-500">Upload Photo</div>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                                <div class="text-center mt-10 text-14 text-light-1">PNG or JPG no bigger than 800px wide and tall.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="button py-20 -dark-1 bg-blue-1 text-white mx-auto d-block" style="width: 530px;"> 
                                    Modifier le profil
                                </button>
                            </div>
                        </form>

                        <div class="row y-gap-20 pt-20">
                            <div class="col-12">
                                <a href="index.php" class="button py-20 -blue-1 bg-blue-1-05 text-blue-1 w-100">
                                    Retournez à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/vendors.js"></script>
    <script src="js/main.js"></script>
    <script>function previewImages(event) {
        const container = document.getElementById('imagePreviewContainer');
        const uploadLabel = container.querySelector('label').parentNode.parentNode.parentNode;
        
        const existingPreviews = container.querySelectorAll('.image-preview');
        existingPreviews.forEach(preview => {
            if (!preview.contains(uploadLabel)) {
                preview.remove();
            }
        });
        
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-auto image-preview';
                
                previewDiv.innerHTML = `
                    <div class="w-200 relative">
                        <div class="d-flex ratio ratio-1:1">
                            <img src="${e.target.result}" alt="preview" class="img-ratio rounded-4">
                            <div class="absolute-full-center d-flex justify-end">
                                <div class="size-40 bg-white rounded-4 flex-center cursor-pointer" onclick="removePreview(this)">
                                    <i class="icon-trash text-16 text-red-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(previewDiv);
            };
            
            reader.readAsDataURL(file);
        }
    }
    function removePreview(element) {
        const previewDiv = element.closest('.image-preview');
        previewDiv.remove();
        
        const container = document.getElementById('imagePreviewContainer');
        const previews = container.querySelectorAll('.image-preview');
        if (previews.length === 0) {
            document.getElementById('imageUpload').value = '';
        }
    }
    </script>
</body>
</html>


