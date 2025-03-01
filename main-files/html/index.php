<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'mysql:dbname=onemix;host=127.0.0.1:8889';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
  if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
    return 9999; 
  }
  
  $earth_radius = 6371;

  $lat1 = deg2rad(floatval($lat1));
  $lon1 = deg2rad(floatval($lon1));
  $lat2 = deg2rad(floatval($lat2));
  $lon2 = deg2rad(floatval($lon2));

  $dlat = $lat2 - $lat1;
  $dlon = $lon2 - $lon1;

  //Formule de Haversine
  $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

  $distance = $earth_radius * $c;

  return $distance;
}

$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$min_hours = isset($_GET['min_hours']) ? intval($_GET['min_hours']) : 2;
$max_distance = isset($_GET['distance']) ? floatval($_GET['distance']) : 5;
$user_lat = isset($_GET['latitude']) ? floatval($_GET['latitude']) : null;
$user_lon = isset($_GET['longitude']) ? floatval($_GET['longitude']) : null;

$sql = "SELECT * FROM studios WHERE 1=1";
$params = [];

if (!empty($city)) {
    $sql .= " AND city LIKE :city";
    $params['city'] = "%$city%";
}

if ($min_hours > 0) {
  $sql .= " AND min_hours <= :min_hours";
  $params['min_hours'] = $min_hours;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$studios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filtered_studios = [];
$has_coordinates = ($user_lat && $user_lon);

foreach ($studios as $studio) {
    if (!$has_coordinates) {
        $studio['distance'] = null;
        $filtered_studios[] = $studio;
        continue;
    }
    
    $distance = calculateDistance($user_lat, $user_lon, $studio['latitude'], $studio['longitude']);
    $studio['distance'] = round($distance, 1);
    
    if ($distance <= $max_distance) {
        $filtered_studios[] = $studio;
    }
}

if ($has_coordinates) {
    usort($filtered_studios, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
}
?>
<!DOCTYPE html>
<html lang="en" data-x="html" data-x-toggle="html-overflow-hidden">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/vendors.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

  <title>OneMix</title>
</head>

<body>
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

    <div class="preloader__title">MixOne</div>
  </div>
  <header data-add-bg="bg-dark-1" class="header bg-green js-header" data-x="header" data-x-toggle="is-menu-opened">
    <div data-anim="fade" class="header__container px-30 sm:px-20">
      <div class="row justify-between items-center">

        <div class="col-auto">
          <div class="d-flex items-center">
            <a href="index.html" class="header-logo mr-20" data-x="header-logo" data-x-toggle="is-logo-dark">
              <img src="img/general/logo-light.svg" alt="logo icon">
              <img src="img/general/logo-dark.svg" alt="logo icon">
            </a>


            <div class="header-menu " data-x="mobile-menu" data-x-toggle="is-menu-active">
              <div class="mobile-overlay"></div>

              <div class="header-menu__content">
                <div class="mobile-bg js-mobile-bg"></div>

                <div class="menu js-navList">
                  <ul class="menu__nav text-white -is-active">

                  <li class="subnav__backBtn js-nav-list-back">

                        <li><a href="index.php">Home</a></li>


                        <li class="subnav__backBtn js-nav-list-back">
                        <li><a href="db-dashboard.php">Dashboard</a></li>
            
                
                  </ul>
                </div>

                <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
                </div>
              </div>
            </div>

          </div>
        </div>


        <div class="col-auto">
          <div class="d-flex items-center">

            <div class="row x-gap-20 items-center xxl:d-none">
              <div class="col-auto">
                <button class="d-flex items-center text-14 text-white" data-x-click="currency">
                  <span class="js-currencyMenu-mainTitle">USD</span>
                  <i class="icon-chevron-sm-down text-7 ml-10"></i>
                </button>
              </div>

              <div class="col-auto">
                <div class="w-1 h-20 bg-white-20"></div>
              </div>

              <div class="col-auto">
                <button class="d-flex items-center text-14 text-white" data-x-click="lang">
                  <img src="img/general/lang.png" alt="image" class="rounded-full mr-10">
                  <span class="js-language-mainTitle">United Kingdom</span>
                  <i class="icon-chevron-sm-down text-7 ml-15"></i>
                </button>
              </div>
            </div>


        <div class="d-flex items-center ml-20 is-menu-opened-hide md:d-none">
          <?php if(isset($_SESSION['user'])): ?>
            <a href="logout.php" class="button px-30 fw-400 text-14 -white bg-white h-50 text-dark-1">Déconnexion</a>
          <?php else: ?>
            <a href="login.php" class="button px-30 fw-400 text-14 -white bg-white h-50 text-dark-1">Se connecter</a>
            <a href="signup.php" class="button px-30 fw-400 text-14 border-white -outline-white h-50 text-white ml-20">Créer un compte</a>
          <?php endif; ?>
      </div>
      
      <div class="d-flex items-center ml-20 is-menu-opened-hide md:d-none">
        <?php if(isset($_SESSION['user'])): ?>
          <a href="profile.php" class="button px-30 fw-400 text-14 border-white -outline-white h-50 text-white ml-20">Modifier le compte </a>
          <?php endif; ?>
          </div>

            <div class="d-none xl:d-flex x-gap-20 items-center pl-30 text-white" data-x="header-mobile-icons" data-x-toggle="text-white">
              <div><a href="login.php" class="d-flex items-center icon-user text-inherit text-22"></a></div>
              <div><button class="d-flex items-center icon-menu text-inherit text-20" data-x-click="html, header, header-logo, header-mobile-icons, mobile-menu"></button></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>


  <main>
    <section data-anim-wrap class="masthead -type-1 z-5">
        <div data-anim-child="fade" class="masthead__bg">
            <img src="" data-src="img/masthead/1/11.jpg" alt="image" class="js-lazy">
        </div>

        <div class="container">
            <div class="row justify-center">
                <div class="col-auto">
                    <div class="text-center">
                        <h1 data-anim-child="slide-up delay-4" class="text-60 lg:text-40 md:text-30 text-white">Découvrez votre prochain studio</h1>
                        <p data-anim-child="slide-up delay-5" class="text-white mt-6 md:mt-10">Trouvez d'incroyables studios aux meilleurs prix</p>
                    </div>

                    <div data-anim-child="slide-up delay-6" class="tabs -underline mt-60 js-tabs">
                        <div class="tabs__content mt-30 md:mt-20 js-tabs-content">
                            <div class="tabs__pane -tab-item-1 is-tab-el-active">
                                <div class="mainSearch -w-900 bg-white px-10 py-10 lg:px-20 lg:pt-5 lg:pb-20 rounded-100">
                                <form id="searchForm" action="index.php" method="GET">
                                    <input type="hidden" id="latitude" name="latitude" value="<?= $user_lat ?>">
                                    <input type="hidden" id="longitude" name="longitude" value="<?= $user_lon ?>">
                                    <input type="hidden" id="distance" name="distance" value="<?= $max_distance ?>">
                                    <div class="button-grid items-center">

                                        <div class="searchMenu-loc px-30 lg:py-20 lg:px-0">
                                            <label for="city" class="text-15 fw-500 ls-2 lh-16">City</label>
                                            <input type="text" id="city" name="city" placeholder="City" value="<?= htmlspecialchars($city) ?>" class="js-search js-dd-focus" />
                                        </div>

                                       
                                        <div class="searchMenu-guests px-30 lg:py-20 lg:px-0 position-relative">
                                            <label for="min_hours" class="text-15 fw-500 ls-2 lh-16">Hours</label>
                                            <input type="text" id="min_hours" name="min_hours" placeholder="Hours" value="<?= $min_hours ?>" class="text-15 text-light-1 ls-2 lh-16" onclick="toggleHoursMenu(event)" readonly />
                                            <div id="hoursMenu" class="hours-menu hidden">
                                                <button type="button" class="button -outline-blue-1 text-blue-1 size-38 rounded-4" onclick="changeHours(-1)">
                                                    <i class="icon-minus text-12"></i>
                                                </button>
                                                <div class="flex-center size-20 ml-15 mr-15">
                                                    <div id="hoursValue" class="text-15"><?= $min_hours ?></div>
                                                </div>
                                                <button type="button" class="button -outline-blue-1 text-blue-1 size-38 rounded-4" onclick="changeHours(1)">
                                                    <i class="icon-plus text-12"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="sidebar__item ">
                                        <label for="distanceSlider" class="text-15 fw-500 ls-2 lh-16">Périmetre</label>
                                            <div class="row x-gap-10 y-gap-30">
                                                <div class="col-12">
                                                    <div class="js-price-rangeSlider">
                                                        <div class="text-14 fw-500"></div>

                                                        <div class="d-flex justify-between mb-20">
                                                            <div class="text-15 text-dark-1">
                                                                <span class="js-lower">0km</span>
                                                                -
                                                                <span class="js-upper"><?= $max_distance ?>km</span>
                                                            </div>
                                                        </div>

                                                        <div class="px-5">
                                                            <div id="distanceSlider" class="js-slider noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-item">
                                            <button type="submit" class="mainSearch__submit button -dark-1 h-60 px-35 col-12 rounded-100 bg-blue-1 text-white">
                                                <i class="icon-search text-20 mr-10"></i>
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hidden {
            display: none;
        }
        .hours-menu {
            display: flex;
            gap: 10px;
            align-items: center;
            position: absolute;
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            z-index: 1000;
            top: 100%;
            left: 0;
        }
        .distance-badge {
            display: inline-block;
            background-color: #e9f5ff;
            color: #3554D1;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>

    <section class="layout-pt-md layout-pb-md">
        <div data-anim="slide-up delay-1" class="container">
            <div class="row y-gap-10 justify-between items-end">
                <div class="col-auto">
                    <div class="sectionTitle -md">
                        <h2 class="sectionTitle__title">Résultats de la recherche</h2>
                        <p class="sectionTitle__text mt-5 sm:mt-0">
                            <?php if (count($filtered_studios) > 0): ?>
                                <?= count($filtered_studios) ?> studio(s) correspondant à vos critères
                                <?php if ($city): ?>près de <strong><?= htmlspecialchars($city) ?></strong><?php endif; ?>
                                <?php if ($has_coordinates): ?>dans un rayon de <strong><?= $max_distance ?>km</strong><?php endif; ?>
                            <?php else: ?>
                                Aucun studio trouvé correspondant à vos critères
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden pt-40 sm:pt-20">
                <div class="row y-gap-30">
                    <?php if (empty($filtered_studios)) : ?>
                        <div class="col-12">
                            <p class="text-center">Aucun studio trouvé dans le périmètre sélectionné. Essayez d'augmenter la distance ou de chercher dans une autre ville.</p>
                        </div>
                    <?php else : ?>
                        <?php foreach ($filtered_studios as $studio) : ?>
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <a href="#" class="hotelsCard -type-1">
                                    <div class="hotelsCard__image">
                                        <div class="cardImage ratio ratio-1:1">
                                            <div class="cardImage__content">
                                            <?php
                                            $images = !empty($studio['images']) ? json_decode($studio['images'], true) : [];
                                            $studioImage = (!empty($images) && file_exists($images[0])) ? $images[0] : 'img/backgrounds/placeholder.jpg'; // Image par défaut
                                            ?>
                                            <img class="col-12" src="<?= htmlspecialchars($studioImage) ?>" alt="<?= htmlspecialchars($studio['name']) ?>">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="hotelsCard__content mt-10">
                                        <h4 class="hotelsCard__title text-dark-1 text-18 lh-16 fw-500">
                                            <?= htmlspecialchars($studio['name']) ?>
                                            <?php if (isset($studio['distance'])): ?>
                                                <span class="distance-badge"><?= $studio['distance'] ?> km</span>
                                            <?php endif; ?>
                                        </h4>
                                        <p class="text-light-1 lh-14 text-14 mt-5"><?= htmlspecialchars($studio['city']) ?></p>
                                        <div class="d-flex items-center mt-20">
                                            <div class="flex-center bg-blue-1 rounded-4 size-30 text-12 fw-600 text-white">4.8</div>
                                            <div class="text-14 text-dark-1 fw-500 ml-10">Exceptional</div>
                                            <div class="text-14 text-light-1 ml-10">3,014 reviews</div>
                                        </div>
                                        <div class="mt-5">
                                            <div class="fw-500">
                                                A Partir de <span class="text-blue-1"><?= htmlspecialchars($studio['hourly_rate']) ?>€</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="layout-pt-md layout-pb-lg">
        <div data-anim-wrap="" class="container animated">
            <div class="row y-gap-20 justify-between">

                <div class="row y-gap-40 justify-between pt-50">

                    <div data-anim-child="slide-up delay-2" class="col-lg-3 col-sm-6 is-in-view">

                        <div class="featureIcon -type-1 ">
                            <div class="d-flex justify-center">
                                <img src="http://127.0.0.1:8000/media/img/featureIcons/1/1.svg" alt="image" class="js-lazy loaded" data-ll-status="loaded">
                            </div>

                            <div class="text-center mt-30">
                                <h4 class="text-18 fw-500">Garantie du meilleur prix</h4>
                                <p class="text-15 mt-10">Nous vous assurons des tarifs compétitifs et transparents sur tous les studios d’enregistrement disponibles sur notre plateforme. Pas de frais cachés, pas de mauvaise surprise !</p>
                            </div>
                        </div>

                    </div>

                    <div data-anim-child="slide-up delay-3" class="col-lg-3 col-sm-6 is-in-view">

                        <div class="featureIcon -type-1 ">
                            <div class="d-flex justify-center">
                                <img src="http://127.0.0.1:8000/media/img/featureIcons/1/2.svg" alt="image" class="js-lazy loaded" data-ll-status="loaded">
                            </div>

                            <div class="text-center mt-30">
                                <h4 class="text-18 fw-500">Réservation facile et rapide</h4>
                                <p class="text-15 mt-10">Trouvez un studio, choisissez votre créneau horaire et réservez en quelques clics. Plus besoin d’appels ou d’échanges compliqués, tout se fait directement en ligne.</p>
                            </div>
                        </div>

                    </div>

                    <div data-anim-child="slide-up delay-4" class="col-lg-3 col-sm-6 is-in-view">

                        <div class="featureIcon -type-1 ">
                            <div class="d-flex justify-center">
                                <img src="http://127.0.0.1:8000/media/img/featureIcons/1/3.svg" alt="image" class="js-lazy loaded" data-ll-status="loaded">
                            </div>

                            <div class="text-center mt-30">
                                <h4 class="text-18 fw-500">Service client 24h/24 et 7j/7</h4>
                                <p class="text-15 mt-10">Notre équipe est disponible à tout moment pour répondre à vos questions et vous accompagner avant, pendant et après votre réservation.</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

    <section class="layout-pt-lg layout-pb-lg bg-blue-2">
        <div data-anim-wrap="" class="container animated">
            <div class="row y-gap-40 justify-between">
                <div data-anim-child="slide-up delay-1" class="col-xl-5 col-lg-6 is-in-view">
                    <h2 class="text-30">What our customers are<br> saying us?</h2>
                    <p class="mt-20">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas varius tortor nibh, sit amet tempor nibh finibus et. Aenean eu enim justo.</p>

                    <div class="row y-gap-30 pt-60 lg:pt-40">
                        <div class="col-sm-5 col-6">
                            <div class="text-30 lh-15 fw-600">13m+</div>
                            <div class="text-light-1 lh-15">Happy People</div>
                        </div>

                        <div class="col-sm-5 col-6">
                            <div class="text-30 lh-15 fw-600">4.88</div>
                            <div class="text-light-1 lh-15">Overall rating</div>

                            <div class="d-flex x-gap-5 items-center pt-10">

                                <div class="icon-star text-blue-1 text-10"></div>

                                <div class="icon-star text-blue-1 text-10"></div>

                                <div class="icon-star text-blue-1 text-10"></div>

                                <div class="icon-star text-blue-1 text-10"></div>

                                <div class="icon-star text-blue-1 text-10"></div>

                            </div>
                        </div>
                    </div>
                </div>

                <div data-anim-child="slide-up delay-2" class="col-lg-6 is-in-view">
                    <div class="overflow-hidden js-testimonials-slider-3 swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden" data-scrollbar="">
                        <div class="swiper-wrapper" id="swiper-wrapper-e3bcca3e4766ba0c" aria-live="polite" style="cursor: grab;">

                            <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 3" style="width: 450px;">
                                <div class="row items-center x-gap-30 y-gap-20">
                                    <div class="col-auto">
                                        <img src="http://127.0.0.1:8000/media/img/avatars/1.png" alt="image" class="js-lazy loaded" data-ll-status="loaded">
                                    </div>

                                    <div class="col-auto">
                                        <h5 class="text-16 fw-500">Annette Black</h5>
                                        <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                                    </div>
                                </div>

                                <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                            </div>

                            <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 3" style="width: 450px;">
                                <div class="row items-center x-gap-30 y-gap-20">
                                    <div class="col-auto">
                                        <img src="http://127.0.0.1:8000/media/img/avatars/1.png" alt="image" class="js-lazy loaded" data-ll-status="loaded">
                                    </div>

                                    <div class="col-auto">
                                        <h5 class="text-16 fw-500">Annette Black</h5>
                                        <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                                    </div>
                                </div>

                                <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                            </div>

                            <div class="swiper-slide" role="group" aria-label="3 / 3" style="width: 450px;">
                                <div class="row items-center x-gap-30 y-gap-20">
                                    <div class="col-auto">
                                        <img src="#" data-src="http://127.0.0.1:8000/media/img/avatars/1.png" alt="image" class="js-lazy">
                                    </div>

                                    <div class="col-auto">
                                        <h5 class="text-16 fw-500">Annette Black</h5>
                                        <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                                    </div>
                                </div>

                                <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                            </div>

                        </div>

                        <div class="d-flex items-center mt-60 sm:mt-20 js-testimonials-slider-pag">
                            <div class="text-dark-1 fw-500 js-current">01</div>
                            <div class="slider-scrollbar bg-border ml-20 mr-20 w-max-300 js-scrollbar"><div class="swiper-scrollbar-drag" style="transform: translate3d(0px, 0px, 0px); width: 100px;"></div></div>
                            <div class="text-dark-1 fw-500 js-all">03</div>
                        </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="layout-pt-md layout-pb-md bg-dark-2">
        <div class="container">
            <div class="row y-gap-30 justify-between items-center">
                <div class="col-auto">
                    <div class="row y-gap-20  flex-wrap items-center">
                        <div class="col-auto">
                            <div class="icon-newsletter text-60 sm:text-40 text-white"></div>
                        </div>

                        <div class="col-auto">
                            <h4 class="text-26 text-white fw-600">Ta session studio commence maintenant</h4>
                            <div class="text-white">Inscris toi pour recevoir les meilleurs offres</div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="single-field -w-410 d-flex x-gap-10 y-gap-20">
                        <div>
                            <input class="bg-white h-60" type="text" placeholder="Your Email">
                        </div>

                        <div>
                            <button class="button -md h-60 bg-blue-1 text-white">S'abonner</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  </main>

    <footer class="footer -type-1">
      <div class="container">
          <div class="pt-60 pb-60">
              <div class="row y-gap-40 justify-between xl:justify-start">
                  <div class="col-xl-2 col-lg-4 col-sm-6">
                      <h5 class="text-16 fw-500 mb-30">Nous Contacter</h5>

                      <div class="mt-35">
                          <div class="text-14 mt-30">Besoin d'aide ?</div>
                          <a href="#" class="text-18 fw-500 text-blue-1 mt-5">team@MixOne.com</a>
                      </div>
                  </div>

                  <div class="col-xl-2 col-lg-4 col-sm-6">
                      <h5 class="text-16 fw-500 mb-30">Entreprise</h5>
                      <div class="d-flex y-gap-10 flex-column">
                          <a href="about.html">A propos</a>
                          <a href="about.html">Careers</a>
                          <a href="contact.html">Nous rejoindre</a>


                      </div>
                  </div>

                  <div class="col-xl-2 col-lg-4 col-sm-6">
                      <h5 class="text-16 fw-500 mb-30">Support</h5>
                      <div class="d-flex y-gap-10 flex-column">
                          <a href="contact.html">Contact</a>
                          <a href="terms.html">Mentions Legales</a>
                          <a href="terms.html">Politique de Confidentialité</a>
                      </div>
                  </div>
              </div>
          </div>

          <div class="py-20 border-top-light">
              <div class="row justify-between items-center y-gap-10">
                  <div class="col-auto">
                      <div class="row x-gap-30 y-gap-10">
                          <div class="col-auto">
                              <div class="d-flex items-center">
                                  © 2025 MixOne LLC All rights reserved.
                              </div>
                          </div>

                          <div class="col-auto">
                              <div class="d-flex x-gap-15">
                                  <a href="terms.html">Confidentialité</a>
                                  <a href="terms.html">Termes</a>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-auto">
                      <div class="row y-gap-10 items-center">
                          <div class="col-auto">
                              <div class="d-flex items-center">
                                  <button class="d-flex items-center text-14 fw-500 text-dark-1 mr-10">
                                      <i class="icon-globe text-16 mr-10"></i>
                                      <span class="underline">English (US)</span>
                                  </button>

                                  <button class="d-flex items-center text-14 fw-500 text-dark-1">
                                      <i class="icon-usd text-16 mr-10"></i>
                                      <span class="underline">USD</span>
                                  </button>
                              </div>
                          </div>

                          <div class="col-auto">
                              <div class="d-flex x-gap-20 items-center">
                                  <a href="#"><i class="icon-facebook text-14"></i></a>
                                  <a href="#"><i class="icon-twitter text-14"></i></a>
                                  <a href="#"><i class="icon-instagram text-14"></i></a>
                                  <a href="#"><i class="icon-linkedin text-14"></i></a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('distanceSlider');
        const lowerValue = document.querySelector(".js-lower");
        const upperValue = document.querySelector(".js-upper");
        
        if (slider) {
            noUiSlider.create(slider, {
                start: [<?= $max_distance ?>],
                connect: [true, false],
                range: {
                    'min': 0,
                    'max': 200
                },
                step: 5,
                format: {
                    to: function (value) {
                        return Math.round(value) + "km";
                    },
                    from: function (value) {
                        return Number(value.replace("km", ""));
                    }
                }
            });

            slider.noUiSlider.on("update", function (values) {
                lowerValue.textContent = "0km";
                upperValue.textContent = values[0];
                document.getElementById('distance').value = parseInt(values[0]);
            });
        }

        window.toggleHoursMenu = function(event) {
            event.stopPropagation();
            const menu = document.getElementById('hoursMenu');
            menu.classList.toggle('hidden');
        }

        window.changeHours = function(amount) {
            const hoursInput = document.getElementById('min_hours');
            const hoursValue = document.getElementById('hoursValue');
            let currentValue = parseInt(hoursValue.textContent);
            if (!isNaN(currentValue)) {
                currentValue += amount;
                if (currentValue < 1) {
                    currentValue = 1;
                }
                hoursValue.textContent = currentValue;
                hoursInput.value = currentValue;
            }
        }

        document.addEventListener('click', function(event) {
            const hoursInput = document.getElementById('min_hours');
            const hoursMenu = document.getElementById('hoursMenu');

            if (!hoursInput.contains(event.target) && !hoursMenu.contains(event.target)) {
                hoursMenu.classList.add('hidden');
            }
        });

        window.getUserLocation = function(callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    console.log("Position GPS récupérée:", position.coords.latitude, position.coords.longitude);
                    if (callback) callback();
                }, function(error) {
                    console.warn("Erreur GPS:", error.message);
                    if (callback) callback();
                });
            } else {
                console.warn("Géolocalisation non supportée");
                if (callback) callback();
            }
        }

        window.getCoordinatesFromCity = function(city, callback) {
            if (!city) {
                if (callback) callback();
                return;
            }
            
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(city)}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat;
                        document.getElementById('longitude').value = data[0].lon;
                        console.log("Coordonnées de la ville:", data[0].lat, data[0].lon);
                    } else {
                        console.warn("Ville non trouvée!");
                    }
                    if (callback) callback();
                })
                .catch(error => {
                    console.error("Erreur API:", error);
                    if (callback) callback();
                });
        }

        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const city = document.getElementById('city').value.trim();
            const min_hours = document.getElementById('min_hours').value;
            const distance = document.getElementById('distance').value;
            
            if (city !== "") {
                getCoordinatesFromCity(city, () => this.submit());
            } else {
                getUserLocation(() => this.submit());
            }
        });

        const hasSearch = <?= !empty($_GET) ? 'true' : 'false' ?>;
        const hasCoordinates = <?= ($user_lat && $user_lon) ? 'true' : 'false' ?>;
        
        if (!hasSearch && !hasCoordinates) {
            setTimeout(function() {
                if (confirm("Voulez-vous utiliser votre position actuelle pour trouver les studios à proximité?")) {
                    getUserLocation();
                }
            }, 1000);
        }
    });
  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
</body>

</html>