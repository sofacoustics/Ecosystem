<?php
    use App\Models\User;
    use App\Models\Datafile;
    use App\Models\Widget;
    use App\Events\Test;

    $user = User::find(1);
    $datafile = Datafile::find(2);
    $widget = Widget::find(1);
?>
