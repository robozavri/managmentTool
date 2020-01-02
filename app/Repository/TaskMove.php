<?php


namespace App\Repository;


use App\Task;

class TaskMove
{
    public function moveAllSubChildes($Task,$status)
    {
        $childs = Task::where('parentId',$Task->id)->get();
        foreach($childs as $child)
        {
            if($child->childCount > 0)
            {
                $this->moveAllSubChildes($child,$status);
            }
            $child->status = $status;
            $child->save();
        }
    }
}
