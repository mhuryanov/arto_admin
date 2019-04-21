<?php
    function showQHiracy($questions, $story) {

        if(!empty($questions))
        {
            foreach($questions as $record)
            {
        ?>
        <div class="q-item-container">
            <div class="q-item">
                <div><?php echo $record['question'] ?></div>
                <div class="text-center">
                    <button class="btn btn-success add-new-q-modal-btn" data-qid="<?=$record['id']?>"><i class="fa fa-plus"></i> Add new</button>
                    <a class="btn btn-sm btn-info" href="<?php echo base_url().'editQuestion/'.$story['id']."/".$record['id']; ?>" title="Edit Story"><i class="fa fa-pencil"></i> Edit</a>
                    <a class="btn btn-sm btn-danger deleteUser" href="<?php echo base_url().'deleteQuestion/'.$record['id']."/".$story['id']; ?>" data-userid="<?php echo $record['id']; ?>" title="Delete Story"><i class="fa fa-trash"></i> Delete</a>
                </div>
            </div>
            <?php
                if(isset($record['subs']) && count($record['subs']) > 0) {
                    showQHiracy($record['subs'], $story);
                }
            ?>
        </div>
        <?php
               
            }
        }

    }
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Question Management
        <small>Add, Edit, Delete Question</small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewQuestion/<?=$story['id']?>"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Question List of <b><?= $story['story_name']?></b></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <?php 
                            // var_dump($questions)
                        ?>
                        <?php
                            showQHiracy($questions, $story);
                        ?>
                    
                    
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
    
</div>


<!-- Add New Question Modal -->
<div class="modal fade" id="add-new-q-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Question</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" id="add-new-q-input" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="add-new-q-submit">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        
        </div>
        
    </div>
</div>

<style>
    .q-item{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border: solid 1px #888;
        margin-bottom: 20px;
        box-shadow: 2px 1px 7px 1px #888;
    }

    .q-item:hover{
        cursor: pointer;
        background: #ddd;
    }

    .q-item-container{
        margin-left: 50px;
    }
</style>

<script>
    var qid;
    var story_id = <?=$story['id']?>;
    $(".add-new-q-modal-btn").click(function () {
        qid = $(this).data("qid");
        
        $("#add-new-q-modal").modal({
            backdrop: false
        });
    });

    $("#add-new-q-submit").click(function () {
        var q = $("#add-new-q-input").val();
        if(q == "") {
            alert("Please input Question.")
            return;
        }

        $.ajax({
            url: "<?=base_url()?>/addq_b",
            type: "post",
            data: {
                q: q,
                qid: qid,
                story_id: story_id
            },
            success: function(res) {
                $("#add-new-q-modal").modal("hide");
                location.reload();
            }
        }).fail(function(){
            alert("Error");
        });
       
    });
</script>