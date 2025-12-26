<?php
$meta_page_title = 'top';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!isset($_POST['difficulty'])) {
    header('Location:/top');
    exit;
  }
  $difficulty = $_POST['difficulty'];
  if ($difficulty == 'easy') {
    header('Location:/main?d=1');
    exit;
  } elseif ($difficulty == 'normal') {
    header('Location:/main?d=2');
    exit;
  } elseif ($difficulty == 'hard') {
    header('Location:/main?d=3');
    exit;
  }
}
unset($_SESSION['play_count_added']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include 'templates/head.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      scrollbar-width: none;
      /* background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 30' preserveAspectRatio='none'%3E%3Cpath d='M0,0 C150,40 350,0 500,20 C650,40 800,10 1000,30 C1100,20 1200,0 1200,0 L1200,30 L0,30 Z' style='fill: %23a8d8ea;'/%3E%3C/svg%3E") repeat-x; */
    }

    .full-height-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 1rem;
      box-sizing: border-box;
      overflow-y: auto;
    }

    .difficulty-image {
      width: 100%;
      height: auto;
      border-radius: 10px;
      max-height: 180px;
      /* 小さい画面でも収まるように縮小 */
      object-fit: cover;
    }

    .btn {
      font-size: 1.2rem;
      padding: 0.5rem 1rem;
    }

    @media (min-height: 601px) and (max-height: 700px) {
      .difficulty-image {
        max-height: 100px;
      }

      .btn {
        font-size: 1rem;
        padding: 0.4rem 0.8rem;
      }

      .otaku-title {
        font-size: 40px;
      }
    }

    .otaku-title {
      font-family: 'UnifrakturCook', cursive;
      font-size: 100px;
      color: rgb(202, 189, 144);
      margin-top: 20px;
    }

    @media (max-height: 800px) {
      header {
        display: none;
      }

      .otaku-title {
        font-size: 36px;
        margin-top: 0;
      }

      .difficulty-image {
        max-height: 120px;
      }

      .btn {
        font-size: 0.9rem;
        padding: 0.3rem 0.6rem;
      }

      .card.p-4 {
        padding: 1rem !important;
      }

      .container.full-height-wrapper {
        padding: 0.5rem;
        max-width: 600px !important;
        overflow-x: hidden;
      }

      .fw-bold {
        position: relative;
        top: -5px;
      }

      hr {
        margin: -0.5rem;
      }
    }

    .difficulty-option {
      border: 7px solid transparent;
      border-radius: 50px;
      padding: 10px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    @media (max-height: 800px) {
      header {
        display: none;
      }

      .body {
        padding-top: 0 !important;
        max-height: 600px;
      }

      .btn {
        font-size: 1.0rem !important;
      }

      .mb-3 {
        margin-bottom: 0 !important;
      }
    }

    .difficulty-option:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .difficulty-option.selected {
      border-color: #7a9fd5;
    }

    .difficulty-radio {
      display: none;
    }

    img {
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;

    }

    .btn {
      font-family: "WDXL Lubrifont TC", sans-serif;
      font-weight: 400;
      font-style: normal;
      border-radius: 50px;
      font-size: 1.5rem;
      padding: 0.75rem 1.5rem;
    }

    .btn-start:hover {
      color: #000;
    }

    .btn-start {
      background: linear-gradient(135deg, rgba(255, 235, 150, 0.8), rgba(255, 200, 60, 0.8));
      border: none;
      color: #333;
      font-weight: bold;
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
    }

    .btn-start:hover {
      background: linear-gradient(135deg, rgba(255, 245, 180, 1), rgba(255, 180, 40, 1));
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      transform: translateY(-2px);
    }

    .btn-otaku {
      background: linear-gradient(135deg, rgba(0, 123, 255, 0.6), rgba(0, 93, 199, 0.6));
      border: none;
      color: #fff;
      font-weight: bold;
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
    }

    .btn-otaku:hover {
      background: linear-gradient(135deg, rgba(30, 143, 255, 0.8), rgba(0, 93, 199, 0.8));
      box-shadow: 0 4px 12px rgba(0, 93, 199, 0.3);
      transform: translateY(-2px);
      color: white;
    }

    .btn-pro {
      background: linear-gradient(135deg, rgba(220, 0, 0, 0.6), rgba(150, 0, 0, 0.6));
      border: none;
      color: #fff;
      font-weight: bold;
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
    }

    .btn-pro:hover {
      background: linear-gradient(135deg, rgba(240, 0, 0, 0.8), rgba(100, 0, 0, 0.8));
      box-shadow: 0 4px 12px rgba(150, 0, 0, 0.3);
      transform: translateY(-2px);
      color: white;
    }

    .btn-rank {
      background: linear-gradient(135deg, rgba(0, 221, 0, 0.6), rgba(0, 255, 42, 0.6));
      border: none;
      color: #fff;
      font-weight: bold;
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
    }

    .btn-rank:hover {
      background: linear-gradient(135deg, rgba(8, 240, 0, 0.8), rgba(72, 255, 0, 0.8));
      box-shadow: 0 4px 12px rgba(150, 0, 0, 0.3);
      transform: translateY(-2px);
      color: white;
    }

    @media (max-height: 800px) {
      header {
        display: none;
      }

      body {

        padding-top: 0 !important;
      }

      .container .full-height-wrapper {
        padding-top: 0 !important;
        max-height: 500px;
      }
    }
  </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
  <main>
    <div class="container full-height-wrapper">
      <div class="w-100" style="max-width: 700px;">
        <div class="otaku-title">OtakuType</div>
        <form method="POST" action="top">
          <!-- 難易度設定 -->
          <div class="mb-3">
            <hr>
            <div class="fw-bold mb-3" style="font-size: 1.2rem;">難易度設定</div>
            <div class="row text-center">
              <div class="col-12 col-md-4 mb-3 mb-md-0">
                <label class="difficulty-option" id="label-easy">
                  <input type="radio" name="difficulty" id="easy" value="easy" class="difficulty-radio" checked>
                  <img src="../assets/img/1.png" alt="初級" class="difficulty-image">
                </label>
              </div>
              <div class="col-12 col-md-4 mb-3 mb-md-0">
                <label class="difficulty-option" id="label-normal">
                  <input type="radio" name="difficulty" id="normal" value="normal" class="difficulty-radio">
                  <img src="../assets/img/2.png" alt="中級" class="difficulty-image">
                </label>
              </div>
              <div class="col-12 col-md-4">
                <label class="difficulty-option" id="label-hard">
                  <input type="radio" name="difficulty" id="hard" value="hard" class="difficulty-radio">
                  <img src="../assets/img/3.png" alt="上級" class="difficulty-image">
                </label>
              </div>
            </div>
          </div>

          <div class="card p-4">
            <div class="d-grid gap-3">
              <button type="submit" class="btn btn-start">スタート</button>
              <button type="button" class="btn btn-otaku" onclick="location.href='howtoplay'">あそびかた</button>
              <button type="button" class="btn btn-pro" onclick="location.href='profile'">しょうご’’う・フ*ロフィール</button>
              <button type="button" class="btn btn-rank" onclick="location.href='ranking?d=ea'">ランキング</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <div class="image-left"></div>
  <div class="image-right"></div>

  <?php include 'templates/footer.php' ?>
  <?php include 'templates/script.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const radios = document.querySelectorAll('input[name="difficulty"]');
    const labels = document.querySelectorAll('.difficulty-option');

    function updateSelection() {
      labels.forEach(label => label.classList.remove('selected'));
      const selected = document.querySelector('input[name="difficulty"]:checked');
      if (selected) {
        document.querySelector(`#label-${selected.value}`).classList.add('selected');
      }
    }

    radios.forEach(radio => {
      radio.addEventListener('change', updateSelection);
    });

    window.addEventListener('DOMContentLoaded', updateSelection);

    function difficulty() {
      alert(`難易度を選んでください。`);
      location.href = `top`;
    }
  </script>
</body>

</html>