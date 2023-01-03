<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    function has_child($posts, $id)
    {
        foreach ($posts as $v) {
            if($v->parent_id == $id ) return true;
        }
        return false;
    }

    public function recursive($posts, $parent_id = 0, $level = 0)
    {
        $result = array();
        foreach($posts as $v){
            if($v->parent_id == $parent_id){
                $v->level = $level;
                $result[] = $v;
                if($this->has_child($posts, $v->id)){
                    $result_child = $this->recursive($posts, $v->id, $level + 1);
                    $result = array_merge($result, $result_child);
                }
            }
        }
        return $result;
    }

    public function render_menu($data, $menu_id = "main-menu", $menu_class="sub-menu", $parent_id = 0, $level = 0){
        if($level == 0)
            $result = "<ul id='{$menu_id}'>";
        else
            $result = "<ul class='{$menu_class}'>";
            foreach($data as $v){
                if($v->parent_id == $parent_id){
                    $result .= "<li>";
                    $result .= "<a href=''>{$v->title}</a>";

                    if($this->has_child($data, $v->id)){
                        $result .= $this->render_menu($data, $menu_id, $menu_class, $v->id, $level + 1);
                    }
                    $result .= "</li>";
                }
            }
        $result .= "</ul>";
        return $result;
    }
}
