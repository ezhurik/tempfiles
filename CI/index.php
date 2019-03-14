<?php $this->load->view('common_files/header'); ?>

<div class="container">
    <div class="row" style="position: relative;">
        <h1>Listing</h1>
        <a href="<?= base_url('crud/manage') ?>" class="add-right">
            <button>Add</button>
        </a>
    </div>
    <div class="row">
        <table id="listTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Username</th>
                <th>Company Name</th>
                <th>Locations</th>
                <th>Roles</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($gridData as $row) {
                ?>
                <tr>
                    <td><?= $row['user_id']; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['company_name']; ?></td>
                    <td>
                        <?php
                        $decodedLocation = json_decode($row['company_location']);
                        foreach ($decodedLocation as $locationRow) {
                            echo "<span class='span-location'>" . $locationRow . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $decodedRoles = json_decode($row['roles']);
                        foreach ($decodedRoles as $rolesRow) {
                            echo "<span class='span-location'>" . $rolesRow . "</span>";
                        }
                        ?>
                    </td>
                    <td><?= $row['status'] == "1" ? "Enabled" : "Disabled"; ?></td>
                    <td>
                        <a href="<?= base_url('crud/manage/') . $row['user_id']; ?>">
                            <button>Edit</button>
                        </a>
                        <a href="<?= base_url('crud/delete/') . $row['user_id']; ?>">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
<!--            <tfoot>-->
<!--            <tr>-->
<!--                <th>User Id</th>-->
<!--                <th>Username</th>-->
<!--                <th>Company Name</th>-->
<!--                <th>Locations</th>-->
<!--                <th>Roles</th>-->
<!--                <th>Status</th>-->
<!--            </tr>-->
<!--            </tfoot>-->
        </table>
    </div>
</div>

<?php $this->load->view('common_files/footer'); ?>
<script>
    $(document).ready(function () {
        $('#listTable').DataTable();
    });
</script>
