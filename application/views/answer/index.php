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

<div>
    <button onClick="save()">Save</button>
</div>


<style>
.story-question{
    margin-left: 20px;
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
</script>

