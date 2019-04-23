<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


<script>
    var editors = [];
</script>

<?php
    function showQHiracy($questions, $story) {

        if(!empty($questions))
        {
            foreach($questions as $question)
            {
        ?>
        <div class="q-item-container">
            <div class="q-item">
                <h3><?=$question['question']?></h3>
                <div id="editor-<?=$question['id']?>"></div>
                <div class="save-btn-div">
                    <button class="save-btn" data-qid="<?=$question['id']?>" data-storyid="<?=$story['id']?>">Save</button>
                </div>
                <script>
                    var editor = CKEDITOR.appendTo("editor-<?=$question['id']?>");
                    editors.push({editor: editor, question: <?=$question['id']?>, story: <?=$story['id']?>});
                </script>
            </div>
            <?php
                if(isset($question['subs']) && count($question['subs']) > 0) {
                    showQHiracy($question['subs'], $story);
                }
            ?>
        </div>
        <?php
               
            }
        }

    }
?>

<div class="row">
    <div class="col-md-7">
        <?php
        foreach ($stories as $story) {
        ?>
            <h2 id="story-<?=$story['id']?>" style="background: <?=$story['color']?>" class="story-title"><?=$story['story_name']?></h2>
            <div class="story-question">
            <?php
                showQHiracy($story['questions'], $story);
            ?>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="col-md-5">
        <div class="story-stage" style="height: <?=(70+(count($stories)) * 20)?>px; padding-top: <?=(5+(count($stories)-1) * 20)?>px">
        <?php
            $i = 0;
            foreach ($stories as $story) {
                ?>
                <a href="#story-<?=$story['id']?>" class="story-stage-item" style="border-color: <?=$story['color']?>; margin-top: <?=-$i*20?>px">
                    <?=$story['story_name']?>
                </a>
                <?php
                $i++;
            }
        ?>
        <div>
    </div>
</div>
<!-- <div class="save-btn-div">
    <button class="save-btn" onClick="save()">Save</button>
</div> -->


<style>
.story-title {
    padding: 10px;
}

.story-question{
    margin-left: 50px;
    /* border-left: solid 3px; */
}

.save-btn-div{
    display: flex;
    justify-content: center;
    padding: 20px;
}

.save-btn{
    padding: 5px 30px;
    border: none;
    font-size: 30px;
    background: #2b6ca3;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 2px 4px 9px 1px #555;
}

.q-item-container{
    margin-left: 50px;
    /* border-left: solid 2px; */
}

.story-stage{
    display: flex;
    margin-top: 50px;
    padding: 10px;
    border: solid 3px #000;
    position: fixed;
}

.story-stage-item{
    padding: 5px;
    border-left: solid 5px #000;
    border-top: solid 5px #000;
    margin-left: 7px;
    display: flex;
    width: 70px;
    height: 70px;
    cursor: pointer;
}

.story-stage-item:hover{
    background: #eee;
}
</style>


<script>
    var data = CKEDITOR.instances;
    console.log(data);
    console.log(editors);
    var postData = [];

    function save() {
        postData = [];
        for(var i = 0; i < editors.length; i++) {
            postData.push({
                answer : editors[i]['editor'].getData(), 
                question_id : editors[i]['question'], 
                story_id : editors[i]['story']
            });
            if(editors[i]['editor'].getData() ==  '') {
                alert("Please input all answers!");
                return;
            }
        }


        $.ajax({
            url: "<?=base_url()?>postAnswer",
            type: "post",
            data: {data: postData},
            success: function(res) {
                alert(res);
            }
        })
    }

    $(".save-btn").click(function() {
        var qid = $(this).data("qid");
        var storyid = $(this).data("storyid");
        for(var i = 0; i < editors.length; i++) {
            if(editors[i].question == qid) {
                // alert(qid);
                console.log(editors[i].editor.getData());
            }
        }
    });

</script>

