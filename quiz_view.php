<?php
$quiz_questions = $this->crud_model->get_quiz_questions($lesson_details['id']);
?>

<style>

#clock{

    font-family: 'Orbitron', cursive;
    background: rgba(0, 180, 249, 0.1);
    border-radius: 5px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(4.4px);
    -webkit-backdrop-filter: blur(4.4px);
    border: 1px solid rgba(0, 180, 249, 1);
    width: 100%;
    height: 40px;
    margin: 0;
    padding: 5px;
    position: sticky;

    box-shadow: 2px 0px 9px -1px rgba(29,220,237,1);
    -webkit-box-shadow: 2px 0px 9px -1px rgba(29,220,237,1);
    -moz-box-shadow: 2px 0px 9px -1px rgba(29,220,237,1);

}


</style>

<div id="quiz-body">
    <div class="" id="quiz-header">
        <?php echo get_phrase("quiz_title"); ?> : <strong><?php echo $lesson_details['title']; ?></strong><br>
        <?php echo get_phrase("number_of_questions"); ?> : <strong><?php echo count($quiz_questions->result_array()); ?></strong><br>
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <button id="start_page" type="button" name="button" class="btn btn-sign-up mt-2" style="color: #fff;" onclick="getStarted(1)"><?php echo get_phrase("get_started"); ?></button>
        <?php endif; ?>
    </div>

    <form class="" id="quiz_form" action="" method="post">
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <?php foreach ($quiz_questions->result_array() as $key => $quiz_question):
                $options = json_decode($quiz_question['options']);
            ?>

                <input type="hidden" name="lesson_id" value="<?php echo $lesson_details['id']; ?>">

                <div class="hidden" id = "question-number-<?php echo $key+1; ?>">

                    <div class="row justify-content-center">
                        <div class="col-lg-8">

                            <div class="card text-left">
                                <div class="card-body">

                                    <h6 class="card-title"><?php echo get_phrase("question").' '.($key+1); ?> : <strong><?php echo $quiz_question['title']; ?></strong></h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php
                                    foreach ($options as $key2 => $option): ?>
                                    <li class="list-group-item quiz-options">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="<?php echo $quiz_question['id']; ?>[]" value="<?php echo $key2+1; ?>" id="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2+1; ?>" onclick="enableNextButton('<?php echo $quiz_question['id'];?>')">
                                            <label class="form-check-label" for="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2+1; ?>">
                                                <?php echo $option; ?>
                                            </label>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="justify-content-end"  style="float: right; margin: 5px 0px 40px 40px;">
                                <div class="card">
                                    <div class="card-body" id="clock">    
                                        <h5 class="text-right m-1"> Quiz Time: 00:<span class="" id="time">00</span></h5>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <button type="button" name="button" class="btn btn-sign-up mt-2 mb-2" id="next-btn-<?php echo $quiz_question['id'];?>" style="color: #fff;" <?php if(count($quiz_questions->result_array()) == $key+1):?>onclick="submitQuiz()"<?php else: ?>onclick="showNextQuestion('<?php echo $key+2; ?>')"<?php endif; ?> disabled><?php echo count($quiz_questions->result_array()) == $key+1 ? get_phrase("check_result") : get_phrase("submit_&_next"); ?></button>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
</div>

<div id="quiz-result" class="text-left">

</div>
<script type="text/javascript">


// document.getElementById("start_page").addEventListener("click", ($quiz_question)=>{
//
// function getStarted(first_quiz_question) {
//
//     var count   =   0;
//     var time    =   30;
//     var marks   =   0;
//     var timer;
//
//     $('#quiz-header').hide();
//     $('#lesson-summary').hide();
//     $('#question-number-'+first_quiz_question).show();
//
//     timer = setInterval(timer_function, 1000);
//
//     function timer_function(){
//
//         $('#time').text(time);
//         if(timer < 1){
//
//             clearInterval(timer);
//             alert('Time is over!');
//             creating_result(response);
//             $('#quiz-result').show();
//
//         }
//         time--;
//
// }
// };
//
// });

function getStarted(first_quiz_question) {
    $('#quiz-header').hide();
    $('#lesson-summary').hide();
    $('#question-number-'+first_quiz_question).show();


    var timer = 30;             //Time counter
    var quiz_questionsCount = 0;       //Questions counter

    //Questions array
    var questions = 'quiz_form';

    questionDivId = document.getElementById('start_page');

    setInterval(function () {
        timer--;

        if (timer >= 0) {
            id = document.getElementById('time');
            id.innerHTML = timer;
        }
        if (timer === 0) {
            id.innerHTML = alert('Time is over. Next Question...');
            timer = 30;
            quiz_questionsCount++;
        }

        //To check if all questions are completed or not will be show the quiz result
        if (quiz_questionsCount === questions.length){
            questionDivId.innerHTML = alert('Your time has finally over. It seems some of your quiz has not been answered.');
            id.innerHTML = "";

            function submitQuiz() {
                $.ajax({
                    url: '<?php echo site_url('home/submit_quiz'); ?>',
                    type: 'post',
                    data: $('form#quiz_form').serialize(),
                    success: function(response) {
                        $('#quiz-body').hide();
                        $('#quiz-result').html(response);
                    }

                });



            }

        } else{
            questionDivId.innerHTML = questions[quiz_questionsCount];
        }
    }, 1000)

    //To go to the next question
    function showNextQuestion(next_question) {
        $('#question-number-'+(next_question-1)).show();
        $('#question-number-'+next_question).show();
        quiz_questionsCount++;
        timer = 30;
    }


}



// function showNextQuestion(next_question) {
//     $('#question-number-'+(next_question-1)).show();
//     $('#question-number-'+next_question).show();
//
// }


function submitQuiz() {
    $.ajax({
        url: '<?php echo site_url('home/submit_quiz'); ?>',
        type: 'post',
        data: $('form#quiz_form').serialize(),
        success: function(response) {
            $('#quiz-body').hide();
            $('#quiz-result').html(response);
        }

    });



}



function enableNextButton(quizID) {
    $('#next-btn-'+quizID).prop('disabled', false);


}









</script>
