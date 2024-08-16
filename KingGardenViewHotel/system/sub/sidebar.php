<div class="col-2">
    <div class="card mb-4">
        <div class="card-body">
            <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">Modules</label></div>
            <?php
            $sql = "SELECT * FROM user_modules u JOIN modules m ON u.ModuleId = m.ModuleId WHERE UserID=$user_id";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $ModulePath = $row["ModulePath"];
                $ModuleName = $row["ModuleName"];
                $ModuleClasses = $row["ModuleClasses"];
                echo "<div class='my-3'><a href='$ModulePath'><label class='my-1' style='font-size : 2vh;'><i class='material-icons mx-3'>$ModuleClasses</i>$ModuleName</label></a></div>";
            }
            ?>
        </div>
    </div>
</div>