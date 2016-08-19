<?php

namespace Application;
use Domain\Post;
use Domain\User;

/**
 * Class WritePost
 */
class WritePost
{
    /**
     * @var Post
     */
    private $post;

    /**
     * @var User
     */
    private $user;

    /**
     * WritePost constructor.
     * @param Post $post
     * @param User $user
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}