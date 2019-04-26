<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


<script>
    var editors = [];
    var uid = "<?= uniqid();?>"
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
                    <button class="save-btn save-btn-<?=$question['id']?>" data-qid="<?=$question['id']?>" data-storyid="<?=$story['id']?>">Save</button>
                </div>
                <script>
                    var editor = CKEDITOR.appendTo("editor-<?=$question['id']?>");
                    editors.push({editor: editor, question: <?=$question['id']?>, story: <?=$story['id']?>});
                    editor.on('change', function() {
                        // alert(<?=$question['id']?>);
                        $(".save-btn-<?=$question['id']?>").text("Save");
                    })
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

<div class="row edit-story">
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
        </div>
       
    </div>
</div>

<div class="see-story">
    <button class="see-story-btn">See Story</button>
</div>
<!-- <div class="save-btn-div">
    <button class="save-btn" onClick="save()">Save</button>
</div> -->

<div class="show-story-container">
    
</div>

<div id="loading-div">
    Loading ...
</div>

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

.save-btn, .see-story-btn{
    padding: 5px 30px;
    border: none;
    font-size: 30px;
    background: #2b6ca3;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 2px 4px 9px 1px #555;
    outline: none;
}

.q-item-container{
    margin-left: 50px;
    /* border-left: solid 2px; */
}

.story-stage{
    display: flex;
    margin-top: 50px;
    padding: 10px;
    border: solid 3px #133a94;
    position: fixed;
    top: 25px;
    width: 40%;
    right: 10px;
    background: white;
    border-radius: 10px;
    box-shadow: 4px 4px 18px -4px #133a94;
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

.see-story{
    position: fixed;
    right: 20px;
    bottom: 20px;
}

.edit-story{
    margin:0;
}

.show-story-container{
    display:none;
}

#loading-div{
    position: fixed;
    top: 0;
    left: 0;
    display: none;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    background: #000;
    color: white;
    opacity: 0.8;
}
</style>


<script>
    var data = CKEDITOR.instances;
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

    function showloading(isShow) {
        if(isShow) {
            $("#loading-div").css("display", "flex");
        } else {
            $("#loading-div").css("display", "none");
        }
    }

    $(".save-btn").click(function() {

        $(this).text("Saving...");
        var me = $(this);

        var qid = $(this).data("qid");
        var storyid = $(this).data("storyid");
        
        for(var i = 0; i < editors.length; i++) {
            if(editors[i].question == qid) {
                $.ajax({
                    url: "<?=base_url()?>postInd",
                    type: "post",
                    data: {
                        data: editors[i].editor.getData(),
                        uid: uid,
                        qid: qid,
                        storyid: storyid
                    },
                    success: function(res) {
                        me.text("Saved");
                    }
                })
            }
        }
    });

    var is_story = true;
    $(".see-story-btn").click(function() {
        if(is_story) {
            showloading(true);
            $.ajax({
                url: "<?=base_url()?>showStory/" + uid,
                type: "get",
                success: function(res) {
                    showloading(false);
                    is_story = false;
                    $('.edit-story').hide();
                    $('.show-story-container').show();
                    $(".see-story-btn").text("Back");

                    $('.show-story-container').html(res);
                }
            }).fail(function(err) {
                showloading(false);
                is_story = false;
                $('.edit-story').hide();
                $('.show-story-container').show();
                $(".see-story-btn").text("Back");

                $('.show-story-container').html(err.responseText);
            });
            
        }
        else {
            is_story = true;
            $('.edit-story').show();
            $('.show-story-container').hide();
            $('.show-story-container').html("");
            $(this).text("See Story");
        }
    })
</script>

