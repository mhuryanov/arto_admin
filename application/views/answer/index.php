<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>
    var editors = [];
</script>


<?php
foreach ($stories as $story) {
?>
    <h2><?=$story['story_name']?></h2>
    <div class="story-question">
    <?php
        foreach ($story['quesitons'] as $question) {
        ?>
        <h3><?=$question['question']?></h3>
        <div>
            <div id="editor-<?=$question['id']?>"></div>
        </div>
        <script>
            var editor = CKEDITOR.appendTo("editor-<?=$question['id']?>");
            editors.push({editor: editor, question: <?=$question['id']?>, story: <?=$story['id']?>});
        </script>
        <?php
        }
    ?>
    </div>
<?php
}
?>

<div class="save-btn-div">
    <button class="save-btn" onClick="save()">Save</button>
</div>


<style>
.story-question{
    
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

        console.log(postData);
        $.ajax({
            url: "<?=base_url()?>postAnswer",
            type: "post",
            data: {data: postData},
            success: function(res) {
                alert(res);
            }
        })
    }

    window.resizeTo(
        100,
        2000
    );
    debugger;
</script>

