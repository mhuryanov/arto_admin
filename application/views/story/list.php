<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Story Management
        <small>Add, Edit, Delete Story</small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewStory"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Story List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                    
                        <ul id="sortable">
                        <?php
                            foreach($stories as $record)
                            {
                                ?>
                                    <li class="ui-state-default" style="background: <?=$record['color']?>" data-id="<?=$record['id']?>">
                                        <span><?=$record['story_name']?></span>
                                        <div>
                                            <a class="btn btn-sm btn-primary" href="<?= base_url().'addNewQuestion/'.$record['id']; ?>" title="Add Question"><i class="fa fa-plus"></i> Add Question</a> | 
                                            <a class="btn btn-sm btn-primary" href="<?= base_url().'stories/'.$record['id']."/qlist"; ?>" title="View History"><i class="fa fa-history"></i> View</a> | 
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editStory/'.$record['id']; ?>" title="Edit Story"><i class="fa fa-pencil"></i> Edit</a>
                                            <a class="btn btn-sm btn-danger deleteUser" href="<?php echo base_url().'deleteStory/'.$record['id']; ?>" data-userid="<?php echo $record['id']; ?>" title="Delete Story"><i class="fa fa-trash"></i> Delete</a>
                                        </div>
                                    </li>
                                <?php
                            }
                        ?>
                        </ul>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
    
</div>


<style>
.color-item{
    width: 50px;
    height: 20px;
    display: flex;
    border-radius: 5px;
    cursor: pointer;
    border: solid 3px #888;
}

#sortable{
    padding: 0;
    list-style-type: none;
}

.ui-state-default {
    padding: 15px 5px;
    margin: 12px 0px;
    border: solid 3px #888;
    border-radius: 5px;
    box-shadow: 3px 1px 9px 1px #888;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
<!-- <script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script> -->
  <script>
  $( function() {
    var sortable = $( "#sortable" ).sortable({
        stop: function( event, ui ) {
            
            var listValues = [];
            $("#sortable .ui-state-default").each(function(){
                // console.log($(this).data('id'));
                if($(this).data('id')) {
                    listValues.push($(this).data('id'));
                }
            })
            
            console.log(listValues);

            $.ajax({
                url: "<?=base_url()?>story/order",
                data: {
                    order: listValues
                },
                type: "post",
                success: function(res) {
                    console.log(res);
                }
            });
        }
    });
    $( "#sortable" ).disableSelection();
  } );
  </script>