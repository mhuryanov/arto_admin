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
                    <table class="table table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Created On</th>
                            <th class="text-center">Actions</th>
                        </tr>

                        <?php
                        if(!empty($stories))
                        {
                            foreach($stories as $record)
                            {
                        ?>
                        <tr>
                            <td><?php echo $record['story_name'] ?></td>
                            <td><?php echo date("d-m-Y", strtotime($record['created_at'])) ?></td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary" href="<?= base_url().'addNewQuestion/'.$record['id']; ?>" title="Add Question"><i class="fa fa-plus"></i> Add Question</a> | 
                                <a class="btn btn-sm btn-primary" href="<?= base_url().'stories/'.$record['id']."/qlist"; ?>" title="View History"><i class="fa fa-history"></i> View</a> | 
                                <a class="btn btn-sm btn-info" href="<?php echo base_url().'editStory/'.$record['id']; ?>" title="Edit Story"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-sm btn-danger deleteUser" href="<?php echo base_url().'deleteStory/'.$record['id']; ?>" data-userid="<?php echo $record['id']; ?>" title="Delete Story"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                    
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
    
</div>
<!-- <script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script> -->