<?php
$meta_page_title = 'main';
if (isset($_GET['d'])) {
    $difficulty = $_GET['d'];
    if ($difficulty == '1') {
        $diff = 1;
    } elseif ($difficulty == '2') {
        $diff = 2;
    } elseif ($difficulty == '3') {
        $diff = 3;
    }
} else {
    header('Location:/top');
    exit;
}
$stmt = $pdo->prepare("SELECT jp, romaji FROM questions WHERE diff = :diff ORDER BY RAND() LIMIT 50");
$stmt->bindValue(':diff', $diff, PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include 'templates/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .otaku-que {
            font-family: 'UnifrakturCook', cursive;
            text-align: center;
            font-size: 30px;
            color: rgb(158, 148, 113);
        }

        .card {
            max-width: 700px;
        }

        .btn-otaku {
            font-family: 'UnifrakturCook', cursive;
            border-radius: 50px;
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
        }

        .input_font {
            font-size: 20px;
        }

        .pdt {
            padding-top: 200px;
        }

        #time-plus {
            transition: opacity 0.5s ease;
            position: absolute;
            top: 630px;
            left: 20%;
            transform: translateX(-50%);
            font-size: 3rem;
            font-weight: bold;
            font-family: "WDXL Lubrifont TC", sans-serif;
            display: none;
            color: #218a21;
            z-index: 9999;
        }

        #score-minus {
            transition: opacity 0.5s ease;
            position: absolute;
            top: 630px;
            left: 20%;
            transform: translateX(-50%);
            font-size: 3rem;
            font-weight: bold;
            font-family: "WDXL Lubrifont TC", sans-serif;
            color: red;
            z-index: 9999;
            display: none;
        }

        strong {
            font-family: "WDXL Lubrifont TC", sans-serif;
        }

        .ques {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 40px;
            position: relative;
            top: -30px;
        }

        #timer,
        #score {
            font-family: "WDXL Lubrifont TC", sans-serif;
        }

        .tfont {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 30px;
        }

        .progress {
            background-color: #eee;
            border-radius: 10px;
            overflow: hidden;
            height: 20px;
            width: 70%;
            position: absolute;
            top: -8px;
            left: 71px;
            margin-top: 10px;
        }

        @media (min-width: 1800px) {

            #time-plus,
            #score-minus {
                top: 595px;
                left: 35%;
            }
        }

        @media (max-height: 800px) {
            header {
                display: none;
            }

            body {
                padding-top: 0 !important;
            }

            .otaku-que {
                font-size: 25px;
            }

            .note {
                position: relative;
                top: -35px;
            }

            .container-fluid {
                max-width: 600px !important;
            }

            #time-plus,
            #score-minus {
                top: 295px;
                left: 20%;
            }

            .haa {
                position: relative;
                top: -50px;
            }
        }

        .progress-bar {
            height: 100%;
            width: 100%;
            background-color: limegreen;
            transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }

        .bg-success {
            background-color: limegreen !important;
        }

        .bg-warning {
            background-color: gold !important;
        }

        .bg-danger {
            background-color: red !important;
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pdt">
    <main>
        <div class="container-fluid d-flex justify-content-center align-items-center" style="text-align: center; margin-top: 130px;">
            <div id="time-plus">
                <?php if ($diff == 1) : ?>
                    +３びょう！
                <?php elseif ($diff == 2) : ?>
                    +４びょう！
                <?php elseif ($diff == 3) : ?>
                    +８びょう！
                <?php endif; ?>
            </div>
            <div id="score-minus">
                －20点
            </div>
            <div style="width: 100%; max-width: 700px;" class='note'>
                <div class="d-flex justify-content-between" style="top: -80px;
                            position: relative;">
                    <div><strong>のこり時間:</strong><br> <span class="tfont" id="timer">60</span>秒</div>
                    <div class="progress" style="height: 20px; margin-top: 10px;">
                        <div id="time-bar" class="progress-bar bg-success" style="width: 100%;"></div>
                    </div>
                    <div class="score"><strong>スコア:</strong><br><span class="tfont" id="score">0</span>点</div>
                </div>
                <div class="haa">
                    <div class="ques"><strong>あと:</strong> <span id="remaining-questions">--</span>問</div>
                    <div class="card p-5 w-100 mb-5">
                        <div id="question-text" class="otaku-que">読み込み中...</div>
                        <div class="input_font mt-2">
                            <span id="romaji-guide"></span>
                        </div>
                    </div>
                </div>
                <div class="my-5 fs-5">escで戻る</div>
            </div>
        </div>
    </main>

    <div class="image-left"></div>
    <div class="image-right"></div>

    <?php include 'templates/footer.php' ?>
    <?php include 'templates/script.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const questionElem = document.getElementById("question-text");
        const romajiGuideElem = document.getElementById("romaji-guide");
        const timeElem = document.getElementById("timer");
        const scoreElem = document.getElementById("score");
        const questions = <?php echo json_encode($questions, JSON_UNESCAPED_UNICODE); ?>;
        const remainingElem = document.getElementById("remaining-questions");
        const timeBarElem = document.getElementById("time-bar");
        const romajiAlternatives = {
            shi: ["shi", "si"],
            si: ["si", "shi"],

            sh: ["sh", "sy"],
            sy: ["sy", "sh"],

            chi: ["chi", "ti"],
            cchi: ["cchi", "tti"],
            ti: ["ti", "chi"],

            cyu: ["cyu", "chu"],
            chu: ["chu", "tyu", "cyu"],
            tyu: ["tyu", "chu", "cyu"],

            ch: ["ch", "th", "ty", "cy"],
            th: ["th", "ch"],
            ty: ["ty", "ch", "cy"],
            cy: ["cy", "ty", "ch"],

            cchi: ["cchi", "tti"],
            tti: ["tti", "cchi"],
            tti: ["tti", "cchi", "tchi"],
            tchi: ["tchi", "tti", "cchi"],

            cha: ["cha", "tya"],
            tya: ["tya", "cha"],

            tsu: ["tsu", "tu"],
            tu: ["tu", "tsu"],

            ji: ["ji", "zi"],
            zi: ["zi", "ji"],

            jji: ["jji", "zzi"],
            zzi: ["zzi", "jji"],

            zu: ["zu", "du"],
            du: ["du", "zu"],

            zy: ["zy", "jy", "j"],
            jy: ["jy", "zy", "j"],
            j: ["j", "jy", "zy"],

            jye: ["jye", "j"],

            fu: ["fu", "hu"],
            hu: ["hu", "fu"],

            shy: ["shy", "sy"],

            who: ["who", "wo"],
            wo: ["wo", "who"],

            n: ["n", "nn"],
            nn: ["nn", "n"],

            dhu: ["dhu", "dexyu", "delyu"],
            delyu: ["delyu", "dhu", "dexyu"],
            dexyu: ["dexyu", "dhu", "delyu"],

            dhi: ["dhi", "dexi", "deli"],
            deli: ["deli", "dhi", "dexi"],
            dexi: ["dexi", "dhi", "deli"],

            thi: ["thi", "texi", "teli"],
            texi: ["texi", "thi", "teli"],
            teli: ["teli", "thi", "texi"],

            li: ["li", "xi"],
            xi: ["xi", "li"],

            lyu: ["lyu", "xyu"],
            xyu: ["xyu", "lyu"]
        };

        let currentQuestion = "",
            currentRomaji = "",
            currentRomajiCandidates = [];
        let typedInput = "",
            score = 0,
            time = 60,
            questionIndex = 0;
        let correctKeyCount = 0,
            missTypeCount = 0;
        let startTime = null,
            timerStarted = false,
            noMissThisQuestion = true;

        const diff = <?php echo json_encode($diff); ?>;

        function expandRomaji(romaji) {
            let candidates = [""],
                i = 0;
            while (i < romaji.length) {
                let matched = false;
                for (let len = 3; len >= 1; len--) {
                    const chunk = romaji.slice(i, i + len);
                    if (romajiAlternatives[chunk]) {
                        const alternatives = romajiAlternatives[chunk];
                        candidates = candidates.flatMap(prefix => alternatives.map(a => prefix + a));
                        i += len;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    candidates = candidates.map(c => c + romaji[i]);
                    i++;
                }
            }
            return candidates;
        }
        let firstNAllowed = true;

        function showQuestion() {
            if (questionIndex >= questions.length) return endGame();
            currentQuestion = questions[questionIndex].jp;
            currentRomaji = questions[questionIndex].romaji;
            currentRomajiCandidates = expandRomaji(currentRomaji);
            typedInput = "";
            noMissThisQuestion = true;
            firstNAllowed = true; // 新しい問題でフラグをリセット
            questionElem.textContent = currentQuestion;
            remainingElem.textContent = questions.length - questionIndex;
            updateRomajiGuide();
        }

        function updateRomajiGuide() {
            const guide = currentRomajiCandidates.find(c => c.startsWith(typedInput)) || currentRomaji;
            const correct = guide.slice(0, typedInput.length);
            const remaining = guide.slice(typedInput.length);
            romajiGuideElem.innerHTML = `<span style="color: limegreen;">${correct}</span><span style="color: gray;">${remaining}</span>`;
        }

        function updateTimeDisplay() {
            timeElem.textContent = time;
            const percentage = Math.max(0, Math.min((time / 60) * 100, 100));
            timeBarElem.style.width = `${percentage}%`;

            // 色の変化（緑→黄→赤）
            if (percentage > 50) {
                timeBarElem.className = "progress-bar bg-success";
            } else if (percentage > 20) {
                timeBarElem.className = "progress-bar bg-warning";
            } else {
                timeBarElem.className = "progress-bar bg-danger";
            }
        }

        document.addEventListener("keydown", function(e) {
            if (time <= 0) return;
            const inputChar = e.key.toLowerCase();

            if (!timerStarted && /^[a-z]$/.test(inputChar)) {
                startTimer();
                timerStarted = true;
            }
            const matches = currentRomajiCandidates.filter(c => c.startsWith(typedInput + inputChar));
            if (matches.length > 0) {
                typedInput += inputChar;
                correctKeyCount++;
                currentRomajiCandidates = matches;
                updateRomajiGuide();

                // スコア加算を即時実行
                score += 50;
                scoreElem.textContent = score;

                if (currentRomajiCandidates.includes(typedInput)) {
                    // タイム加算は単語完成時のみ
                    if (noMissThisQuestion) {
                        let added = 0;
                        if (diff == 1) added = 3;
                        else if (diff == 2) added = 4;
                        else if (diff == 3) added = 8;

                        time += added;
                        updateTimeDisplay();

                        const timePlusElem = document.getElementById("time-plus");
                        timePlusElem.textContent = `＋${added}びょう！`;
                        timePlusElem.style.display = "block";
                        timePlusElem.style.opacity = "1";
                        setTimeout(() => {
                            timePlusElem.style.opacity = "0";
                            timePlusElem.style.display = "none";
                        }, 300);
                    }
                    questionIndex++;
                    showQuestion();
                }
            } else {
                if (/^[a-z]$/.test(inputChar)) {
                    if (typedInput === "" && inputChar === "n" && firstNAllowed) {
                        firstNAllowed = false; // 一回使ったのでフラグを無効化
                        return; // ミスカウントしない
                    }
                    missTypeCount++;
                    const scoreminusElem = document.getElementById("score-minus");
                    scoreminusElem.textContent = `－20点`;
                    scoreminusElem.style.display = "block";
                    scoreminusElem.style.opacity = "1";
                    setTimeout(() => {
                        scoreminusElem.style.opacity = "0";
                        scoreminusElem.style.display = "none";
                    }, 300);
                    noMissThisQuestion = false;
                    score = Math.max(0, score - 20);
                    scoreElem.textContent = score;
                }
            }
        });

        document.addEventListener("keyup", function(e) {
            if (e.key === "Escape") {
                window.location.href = "/top";
            }
        });

        function startTimer() {
            startTime = Date.now();
            const timer = setInterval(() => {
                time--;
                updateTimeDisplay();
                if (time <= 0) {
                    clearInterval(timer);
                    endGame(true);
                }
            }, 1000);
        }

        function endGame(isTimeUp = false) {
            const elapsedTimeSec = (Date.now() - startTime) / 1000;
            const averageKeysPerSecond = (correctKeyCount / elapsedTimeSec).toFixed(2);
            const allClearNoMiss = (questionIndex >= questions.length && missTypeCount === 0);
            const remainingCount = isTimeUp ? (questions.length - questionIndex) : 0;

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "result";

            const fields = {
                score,
                d: diff,
                correct: correctKeyCount,
                miss: missTypeCount,
                avg: averageKeysPerSecond,
                cleared: questionIndex >= questions.length,
                allClearNoMiss,
                remainingCount,
                remainingTime: isTimeUp ? 0 : time
            };

            for (const key in fields) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }

        showQuestion();
        updateTimeDisplay();
    </script>

</body>

</html>