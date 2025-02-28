<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = 'localhost:8889';
$dbname = 'onemix';
$username = 'root';
$password = 'root';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
      $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      $name = trim($_POST['name'] ?? '');
      $description = trim($_POST['description'] ?? '');
      $address = trim($_POST['address'] ?? '');
      $zipcode = trim($_POST['zipcode'] ?? '');
      $city = trim($_POST['city'] ?? '');
      $country = trim($_POST['country'] ?? '');
      $hourly_rate = filter_var($_POST['hourly_rate'] ?? 0, FILTER_VALIDATE_FLOAT);
      $min_hours = filter_var($_POST['min_hours'] ?? 0, FILTER_VALIDATE_INT);

      $errors = [];

      if (empty($name)) {
          $errors[] = "Le nom du studio est requis";
      }

      if (!empty($_FILES['images']['name'][0])) {
          $uploadDir = 'uploads/studios/';
          
          if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0777, true);
          }
      
          $imagePaths = [];
          
          foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
              if (!is_uploaded_file($tmpName)) {
                  $errors[] = "Erreur lors de l'upload de l'image " . $_FILES['images']['name'][$key];
                  continue;
              }

              $fileName = basename($_FILES['images']['name'][$key]);
              $filePath = $uploadDir . time() . "_" . $fileName;
              
              if (move_uploaded_file($tmpName, $filePath)) {
                  $imagePaths[] = $filePath;
              } else {
                  $errors[] = "Impossible de déplacer l'image " . $_FILES['images']['name'][$key];
              }
          }
      
          $imagePathsJson = json_encode($imagePaths);
          if (json_last_error() !== JSON_ERROR_NONE) {
              $errors[] = "Erreur lors de l'encodage des images";
          }
      }

      if (empty($address)) {
          $errors[] = "L'adresse est requise";
      }

      if ($hourly_rate <= 0) {
          $errors[] = "Le tarif horaire doit être un nombre positif";
      }

      if ($min_hours <= 0) {
          $errors[] = "Le nombre minimum d'heures doit être un nombre entier positif";
      }

      $latitude = null;
      $longitude = null;

      if (!empty($city) && !empty($country)) {
          $searchQuery = urlencode($city . ", " . $country);
          $nominatimUrl = "https://nominatim.openstreetmap.org/search?q={$searchQuery}&format=json&limit=1";
          
          $options = [
              'http' => [
                  'header' => "User-Agent: OneMixStudioApplication/1.0\r\n"
              ]
          ];
          $context = stream_context_create($options);
          
          $response = file_get_contents($nominatimUrl, false, $context);
          $geocodeData = json_decode($response, true);
          
          if (!empty($geocodeData[0]['lat']) && !empty($geocodeData[0]['lon'])) {
              $latitude = floatval($geocodeData[0]['lat']);
              $longitude = floatval($geocodeData[0]['lon']);
          } else {
              $errors[] = "Impossible de trouver les coordonnées géographiques pour cette ville";
          }
      }

      if (empty($errors)) {
          $user_id = 7;
          $sql = "INSERT INTO studios (user_id, name, address, zipcode, city, country, hourly_rate, min_hours, description, latitude, longitude, images, created_at, updated_at) 
                  VALUES (:user_id, :name, :address, :zipcode, :city, :country, :hourly_rate, :min_hours, :description, :latitude, :longitude, :images, NOW(), NOW())";
          
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
              ':user_id' => $user_id,
              ':name' => $name,
              ':address' => $address,
              ':zipcode' => $zipcode,
              ':city' => $city,
              ':country' => $country,
              ':hourly_rate' => $hourly_rate,
              ':min_hours' => $min_hours,
              ':description' => $description,
              ':latitude' => $latitude,
              ':longitude' => $longitude,
              ':images' => $imagePathsJson
          ]);

          $_SESSION['success_message'] = "Le studio a été ajouté avec succès!";
          header("Location: create-studio.php");
          exit;
      } else {
          var_dump($errors);
          exit;
      }
  } catch (PDOException $e) {
      echo "Erreur de base de données: " . $e->getMessage();
      exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/vendors.css">
  <link rel="stylesheet" href="css/main.css">

  <title>GoTrip</title>
</head>

<body data-barba="wrapper">


  <div class="preloader js-preloader">
    <div class="preloader__wrap">
      <div class="preloader__icon">
        <svg width="38" height="37" viewBox="0 0 38 37" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0_1_41)">
            <path d="M32.9675 13.9422C32.9675 6.25436 26.7129 0 19.0251 0C11.3372 0 5.08289 6.25436 5.08289 13.9422C5.08289 17.1322 7.32025 21.6568 11.7327 27.3906C13.0538 29.1071 14.3656 30.6662 15.4621 31.9166V35.8212C15.4621 36.4279 15.9539 36.92 16.561 36.92H21.4895C22.0965 36.92 22.5883 36.4279 22.5883 35.8212V31.9166C23.6849 30.6662 24.9966 29.1071 26.3177 27.3906C30.7302 21.6568 32.9675 17.1322 32.9675 13.9422V13.9422ZM30.7699 13.9422C30.7699 16.9956 27.9286 21.6204 24.8175 25.7245H23.4375C25.1039 20.7174 25.9484 16.7575 25.9484 13.9422C25.9484 10.3587 25.3079 6.97207 24.1445 4.40684C23.9229 3.91841 23.6857 3.46886 23.4347 3.05761C27.732 4.80457 30.7699 9.02494 30.7699 13.9422ZM20.3906 34.7224H17.6598V32.5991H20.3906V34.7224ZM21.0007 30.4014H17.0587C16.4167 29.6679 15.7024 28.8305 14.9602 27.9224H16.1398C16.1429 27.9224 16.146 27.9227 16.1489 27.9227C16.152 27.9227 23.0902 27.9224 23.0902 27.9224C22.3725 28.8049 21.6658 29.6398 21.0007 30.4014ZM19.0251 2.19765C20.1084 2.19765 21.2447 3.33365 22.1429 5.3144C23.1798 7.60078 23.7508 10.6649 23.7508 13.9422C23.7508 16.6099 22.8415 20.6748 21.1185 25.7245H16.9322C15.2086 20.6743 14.2994 16.6108 14.2994 13.9422C14.2994 10.6649 14.8706 7.60078 15.9075 5.3144C16.8057 3.33365 17.942 2.19765 19.0251 2.19765V2.19765ZM7.28053 13.9422C7.28053 9.02494 10.3184 4.80457 14.6157 3.05761C14.3647 3.46886 14.1273 3.91841 13.9059 4.40684C12.7425 6.97207 12.102 10.3587 12.102 13.9422C12.102 16.7584 12.9462 20.7176 14.6126 25.7245H13.2259C9.33565 20.6126 7.28053 16.5429 7.28053 13.9422Z" fill="#3554D1" />
          </g>

          <defs>
            <clipPath id="clip0_1_41">
              <rect width="36.92" height="36.92" fill="white" transform="translate(0.540039)" />
            </clipPath>
          </defs>
        </svg>
      </div>
    </div>

    <div class="preloader__title">GoTrip</div>
  </div>


  <div class="header-margin"></div>
  <header data-add-bg="" class="header -dashboard bg-white js-header" data-x="header" data-x-toggle="is-menu-opened">
    <div data-anim="fade" class="header__container px-30 sm:px-20">
      <div class="-left-side">
        <a href="index.php" class="header-logo" data-x="header-logo" data-x-toggle="is-logo-dark">
          <img src="img/general/logo-dark.svg" alt="logo icon">
          <img src="img/general/logo-dark.svg" alt="logo icon">
        </a>
      </div>

      <div class="row justify-between items-center pl-60 lg:pl-20">
        <div class="col-auto">
          <div class="d-flex items-center">
            <button data-x-click="dashboard">
              <i class="icon-menu-2 text-20"></i>
            </button>

            <div class="single-field relative d-flex items-center md:d-none ml-30">
              <input class="pl-50 border-light text-dark-1 h-50 rounded-8" type="email" placeholder="Search">
              <button class="absolute d-flex items-center h-full">
                <i class="icon-search text-20 px-15 text-dark-1"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="col-auto">
          <div class="d-flex items-center">

            <div class="header-menu " data-x="mobile-menu" data-x-toggle="is-menu-active">
              <div class="mobile-overlay"></div>

              <div class="header-menu__content">
                <div class="mobile-bg js-mobile-bg"></div>

                <div class="menu js-navList">
                  <ul class="menu__nav text-dark-1 fw-500 -is-active">

                    <li >
                      <a href="index.php">
                        <span class="mr-10">Home</span>
                      </a>
                    <li>
                      <a data-barba href="db-dashboard.php">
                        <span class="mr-10">Dashboard</span>
                        
                      </a>
                  </ul>
                </div>

                <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
                </div>
              </div>
            </div>


            <div class="row items-center x-gap-5 y-gap-20 pl-20 lg:d-none">
              <div class="col-auto">
                <button class="button -blue-1-05 size-50 rounded-22 flex-center">
                  <i class="icon-email-2 text-20"></i>
                </button>
              </div>

              <div class="col-auto">
                <button class="button -blue-1-05 size-50 rounded-22 flex-center">
                  <i class="icon-notification text-20"></i>
                </button>
              </div>
            </div>

            <div class="pl-15">
              <img src="img/avatars/3.png" alt="image" class="size-50 rounded-22 object-cover">
            </div>

            <div class="d-none xl:d-flex x-gap-20 items-center pl-20" data-x="header-mobile-icons" data-x-toggle="text-white">
              <div><button class="d-flex items-center icon-menu text-20" data-x-click="html, header, header-logo, header-mobile-icons, mobile-menu"></button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>


  <div class="dashboard" data-x="dashboard" data-x-toggle="-is-sidebar-open">
    <div class="dashboard__sidebar bg-white scroll-bar-1">


      <div class="sidebar -dashboard">

        <div class="sidebar__item">
          <div class="sidebar__button -is-active">
            <a href="db-dashboard.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/compass.svg" alt="image" class="mr-15">
              Dashboard
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-booking.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/booking.svg" alt="image" class="mr-15">
              Booking History
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-wishlist.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/bookmark.svg" alt="image" class="mr-15">
              Wishlist
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="create-studio.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/hotel.svg" alt="image" class="mr-15">
              Gestion Studio
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-settings.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/gear.svg" alt="image" class="mr-15">
              Settings
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="index.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/log-out.svg" alt="image" class="mr-15">
              Logout
            </a>
          </div>
        </div>

      </div>


    </div>

    <div class="dashboard__main">
    <div class="row y-gap-20 justify-between items-end pb-60 lg:pb-40 md:pb-32">
        <div class="sidebar__item"></div>
        
        <div class="col-auto">
            <h1 class="text-30 lh-14 fw-600">Ajouter un studio</h1>
            <div class="text-15 text-light-1">Lorem ipsum dolor sit amet, consectetur.</div>
        </div>
        
        <div class="col-auto"></div>
    </div>
    
    <div class="py-30 px-30 rounded-4 bg-white shadow-3">
    <?php if (!empty($errors)): ?>
        <div class="row">
            <div class="col-12">
                <div class="px-20 py-20 rounded-4 bg-red-1">
                    <div class="text-white fw-500">Des erreurs sont survenues :</div>
                    <ul class="text-white">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-20"></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="px-20 py-20 rounded-4 bg-green-1">
                    <div class="text-white fw-500"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
                </div>
            </div>
        </div>
        <div class="mt-20"></div>
        <?php 
            unset($_SESSION['success_message']); 
        endif; 
        ?>
        <div class="tabs -underline-2 js-tabs">
            <div class="tabs__controls row x-gap-40 y-gap-10 lg:x-gap-20 js-tabs-controls">
                <div class="col-auto">
                    <button class="tabs__button text-18 lg:text-16 text-light-1 fw-500 pb-5 lg:pb-0 js-tabs-button is-tab-el-active" data-tab-target=".-tab-item-1">1. Contenu</button>
                </div>
                <div class="col-auto">
                    <button class="tabs__button text-18 lg:text-16 text-light-1 fw-500 pb-5 lg:pb-0 js-tabs-button" data-tab-target=".-tab-item-2">2. Localisation</button>
                </div>
                <div class="col-auto">
                    <button class="tabs__button text-18 lg:text-16 text-light-1 fw-500 pb-5 lg:pb-0 js-tabs-button" data-tab-target=".-tab-item-3">3. Tarifs</button>
                </div>
                <div class="col-auto">
                    <button class="tabs__button text-18 lg:text-16 text-light-1 fw-500 pb-5 lg:pb-0 js-tabs-button" data-tab-target=".-tab-item-4">4. Attributs</button>
                </div>
            </div>
            
            <form method="post" action="create-studio.php" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="5IpEaG6d1F3za860R1gHwcj6jwyLlIf6DAdNvuHO" autocomplete="off">                <div class="tabs__content pt-30 js-tabs-content">
                    <div class="tabs__pane -tab-item-1 is-tab-el-active">
                        <div class="col-xl-10">
                            <div class="text-18 fw-500 mb-10">Studio Content</div>
                            <div class="row x-gap-20 y-gap-20">
                                <div class="col-12">
                                    <div class="form-input">
                                        <input type="text" name="name" required="">
                                        <label class="lh-1 text-16 text-light-1">Nom du Studio</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-input">
                                        <textarea name="description" rows="5"></textarea>
                                        <label class="lh-1 text-16 text-light-1">Contenu</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-input">
                                        <input type="text" name="youtube_video">
                                        <label class="lh-1 text-16 text-light-1">Youtube Video</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-30">
                                <div class="fw-500">Gallery</div>
                                <div class="row x-gap-20 y-gap-20 pt-15" id="imagePreviewContainer">
                                    <div class="col-auto">
                                        <div class="w-200">
                                            <div class="d-flex ratio ratio-1:1">
                                                <input type="file" id="imageUpload" name="images[]" accept="image/png, image/jpeg" onchange="previewImages(event)" multiple="" style="display: none;">
                                                <label for="imageUpload" class="flex-center flex-column text-center bg-blue-2 h-full w-1/1 absolute rounded-4 border-type-1 cursor-pointer">
                                                    <div class="icon-upload-file text-40 text-blue-1 mb-10"></div>
                                                    <div class="text-blue-1 fw-500">Ajouter une image</div>
                                                </label>
                                            </div>
                                            <div class="text-center mt-10 text-14 text-light-1">PNG ou JPG pas plus grand que 800px de hauteur et largeur.</div>
                                        </div>
                                    </div>
                                    <!-- Image previews will be added here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tabs__pane -tab-item-2">
                        <div class="col-xl-10">
                            <div class="text-18 fw-500 mb-10">Location</div>
                            <div class="row x-gap-20 y-gap-20">
                                <div class="col-12">
                                    <div class="form-input">
                                        <input type="text" name="address" required="">
                                        <label class="lh-1 text-16 text-light-1">Adresse</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-input">
                                        <input type="text" name="zipcode" required="">
                                        <label class="lh-1 text-16 text-light-1">Code postal</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-input">
                                        <input type="text" name="city" required="" id="city-input">
                                        <label class="lh-1 text-16 text-light-1">Ville</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-input">
                                        <input type="text" name="country" required="" id="country-input">
                                        <label class="lh-1 text-16 text-light-1">Pays</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tabs__pane -tab-item-3">
                        <div class="col-xl-10">
                            <div class="text-18 fw-500 mb-10">Tarifs</div>
                            <div class="row x-gap-20 y-gap-20">
                                <div class="col-6">
                                    <div class="form-input">
                                        <input type="text" name="hourly_rate" required="">
                                        <label class="lh-1 text-16 text-light-1">Tarif horaire</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-input">
                                        <input type="text" name="min_hours" required="">
                                        <label class="lh-1 text-16 text-light-1">Heures minimum</label>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="tabs__pane -tab-item-4">
                        <div class="col-xl-9 col-lg-11">
                            <div class="row x-gap-100 y-gap-15">
                                <div class="col-12">
                                    <div class="text-18 fw-500">Services</div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="row y-gap-15">
                                        <div class="col-12">
                                            <div class="d-flex items-center">
                                                <div class="form-checkbox">
                                                    <input type="checkbox" name="service_apartments">
                                                    <div class="form-checkbox__mark">
                                                        <div class="form-checkbox__icon icon-check"></div>
                                                    </div>
                                                </div>
                                                <div class="text-15 lh-11 ml-10">Apartments</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex items-center">
                                                <div class="form-checkbox">
                                                    <input type="checkbox" name="service_boats">
                                                    <div class="form-checkbox__mark">
                                                        <div class="form-checkbox__icon icon-check"></div>
                                                    </div>
                                                </div>
                                                <div class="text-15 lh-11 ml-10">Boats</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex items-center">
                                                <div class="form-checkbox">
                                                    <input type="checkbox" name="service_holiday_homes">
                                                    <div class="form-checkbox__mark">
                                                        <div class="form-checkbox__icon icon-check"></div>
                                                    </div>
                                                </div>
                                                <div class="text-15 lh-11 ml-10">Holiday homes</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-inline-block mt-30">
                    <button type="submit" class="button h-50 px-24 -dark-1 bg-blue-1 text-white">
                        Enregistrer <div class="icon-arrow-top-right ml-15"></div>
                    </button>
                </div>
            
        
              </div>
            </form>
        </div>
    </div>
    </div>
</div>


  <!-- JavaScript -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
  <script>
    function previewImages(event) {
        const container = document.getElementById('imagePreviewContainer');
        const uploadLabel = container.querySelector('label').parentNode.parentNode.parentNode;
        
        // Supprime les prévisualisations existantes
        const existingPreviews = container.querySelectorAll('.image-preview');
        existingPreviews.forEach(preview => {
            if (!preview.contains(uploadLabel)) {
                preview.remove();
            }
        });
        
        // Ajoute de nouvelles prévisualisations
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
        
        // Réinitialiser l'input file si toutes les prévisualisations sont supprimées
        const container = document.getElementById('imagePreviewContainer');
        const previews = container.querySelectorAll('.image-preview');
        if (previews.length === 0) {
            document.getElementById('imageUpload').value = '';
        }
    }
</script>
</body>

</html>