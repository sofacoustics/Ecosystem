<?php
    use App\Models\User;
    use App\Models\Datafile;
    use App\Models\Tool;
    use App\Events\Test;

    $user = User::find(1);
    $datafile = Datafile::find(2);
    $tool = Tool::find(1);
?>
