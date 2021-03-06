<script type="text/javascript">
	
// select all elements
const start = document.getElementById("start");
const quiz = document.getElementById("quiz");
const question = document.getElementById("question");
const qImg = document.getElementById("qImg");
const choiceA = document.getElementById("A");
const choiceB = document.getElementById("B");
const choiceC = document.getElementById("C");
const counter = document.getElementById("counter");
const timeGauge = document.getElementById("timeGauge");
const progress = document.getElementById("progress");
const scoreDiv = document.getElementById("scoreContainer");
var choices = document.getElementById("choices");

// create our questions
// let questions = [
//     {
//         id : 4,
//         time : 10,
//         question: "What does HTML stand for?",
//         imgSrc: "img/html.png",
//         options : [
//             {
//                 id : 86,
//                 trueoption : "first option"
//             },
//             {
//                 id : 2,
//                 trueoption : "second option"
//             },
//             {
//                 id : 3,
//                 trueoption : "third option"
//             },
//             {
//                 id : 4,
//                 trueoption : "fourth option"
//             },
//         ],
//         correct: "second option"
//     }
// ];

// create some variables

const lastQuestion = questions.length - 1;
let runningQuestion = 0;
let count = 0;
var questionTime = 10; // 10s
const gaugeWidth = 150; // 150px
var gaugeUnit = gaugeWidth / questionTime;
let TIMER;
let score = 0;
//let answersStringToPost = '{"challengeid" : "18", "id" : "31", "challenges" : [';//create the answers table as JSON string to post it directly without using JSON.stringify
let answersTable = [];// table of strings

// render a question
function renderQuestion() {
    let q = questions[runningQuestion];

    question.innerHTML = "<p>" + q.question + "</p>";

    // the choices loop
    let choicesString = "";
    for(let i = 0; i < q.options.length; i++) {
        choicesString += "<div class=\"choice\" id=\"A\" onclick=\"checkAnswer('"+q.options[i].trueoption+"',"+q.id+","+q.options[i].id+",true)\">"+q.options[i].trueoption+"</div>";
    }
    choices.innerHTML = choicesString;

    // the question time
    questionTime = q.time;
    // the progress bar unit
    gaugeUnit = gaugeWidth / questionTime;


    //add loop for choices
    // choiceA.innerHTML = q.choiceA;
    // choiceB.innerHTML = q.choiceB;
    // choiceC.innerHTML = q.choiceC;
}

start.addEventListener("click", startQuiz);

// start quiz
function startQuiz() {
    start.style.display = "none";
    renderQuestion();
    quiz.style.display = "block";
    renderProgress();
    renderCounter(questions[runningQuestion].id, 0, false);
    // change second parameter to a variable 
    TIMER = setInterval(function(){renderCounter(questions[runningQuestion].id, 0, false)}, 1000); // 1000ms = 1s
}

// render progress dots
function renderProgress() {
    for (let qIndex = 0; qIndex <= lastQuestion; qIndex++) {
        progress.innerHTML += "<div class='prog' id=" + qIndex + "></div>";
    }
}

// counter render

function renderCounter(questionid, optionid, time) {// time here is always false
    if (count <= questionTime) {
        counter.innerHTML = count;
        timeGauge.style.width = count * gaugeUnit + "px";
        count++
    } else {
        count = 0;
        // change progress color to red
        answerIsWrong(questionid, optionid, time);
        if (runningQuestion < lastQuestion) {
            runningQuestion++;
            renderQuestion();
        } else {
            // end the quiz and show the score
            clearInterval(TIMER);
            scoreRender();
        }
    }
}

// checkAnwer

function checkAnswer(answer, questionid, optionid, time) {// time here is always true
    if (answer == questions[runningQuestion].correct) {
        // answer is correct
        score++;
        // change progress color to green
        answerIsCorrect(questionid, optionid, time);
    } else {
        // answer is wrong
        // change progress color to red
        answerIsWrong(questionid, optionid, time);
    }
    count = 0;
    if (runningQuestion < lastQuestion) {
        runningQuestion++;
        renderQuestion();
    } else {
        // end the quiz and show the score
        clearInterval(TIMER);
        scoreRender();
    }
}

// answer is correct
function answerIsCorrect(questionid, optionid, time) {
    document.getElementById(runningQuestion).style.backgroundColor = "#0f0";
    answersTable.push('{"time" : "'+time+'", "questionid" : "'+questionid+'", "optionid" : "'+optionid+'"}');
}

// answer is Wrong
function answerIsWrong(questionid, optionid, time) {
    document.getElementById(runningQuestion).style.backgroundColor = "#f00";
    answersTable.push('{"time" : "'+time+'", "questionid" : "'+questionid+'", "optionid" : "'+optionid+'"}');
}

// score render
function scoreRender() {
    scoreDiv.style.display = "block";
    
    // making the json string to post to (check answers + update DB     trello)
    for(let i = 0; i < answersTable.length; i++) {
        answersStringToPost += answersTable[i];
        if(i != answersTable.length-1) {
            answersStringToPost += ',';
        }
    }
    answersStringToPost += ']}';

    // posting to data base to update DB and get the totalwin points
    var x = new XMLHttpRequest();
	x.open("GET", "pages/startQuiz/checkAnswers.php?answersStringToPost="+answersStringToPost, true);
	x.send();
	x.onreadystatechange = 	function () {
		if(this.readyState == 4 && this.status == 200) {
			var score = this.responseText; 
			console.log(score);
			scoreDiv.innerHTML += "<p>" + score + "&nbspPts</p>";
			setTimeout(redirect, 5000);// redirect to main after the challenge is over
		}
	}


    /* calculate the amount of question percent answered by the user
    const scorePerCent = Math.round(100 * score/questions.length); */ 
}

function redirect() {
	window.location.href = "main.php";
}


</script>