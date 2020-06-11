<?php
/**
 * Created by PhpStorm.
 * User: saeed.bhuta
 * Date: 29/09/2017
 * Time: 11:06
 */

namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class CommentRepository extends  EloquentRepository
{
    protected $repositoryId = 'accuen.repository.comment';

    protected $model = 'App\Comment';


}