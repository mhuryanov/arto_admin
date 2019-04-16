<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

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
            <textarea name="editor-<?=$question['id']?>"></textarea>
        </div>
        <script>
            CKEDITOR.replace("editor-<?=$question['id']?>");
        </script>
        <?php
        }
    ?>
    </div>
<?php
}
?>

<style>
.story-question{
    margin-left: 20px;
}
</style>

