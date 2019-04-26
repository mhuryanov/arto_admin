<?php

function showQHiracy($questions, $story) {
    
    if(!empty($questions))
    {
        foreach($questions as $question)
        {
            
            if($question['answer'] != "") {
                echo $question['answer'];
            }
            if(isset($question['subs']) && count($question['subs']) > 0) {
                showQHiracy($question['subs'], $story);
            }

        }

    }

}

if(count($answers) == 0) {
    ?>
        <h2>There is not any Answers</h2>
    <?php
} else {
    foreach ($stories as $story) {
    ?>
        <h2 id="story-<?=$story['id']?>" style="background: <?=$story['color']?>" class="story-title"><?=$story['story_name']?></h2>
    <?php
        showQHiracy($story['questions'], $story);
        
    }
}

?>