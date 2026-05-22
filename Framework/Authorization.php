<?php

namespace Framework;

use Framework\Session;

class Authorization{
    /**
     * Check if logged in user owns a listing
     * 
     * @params int $resourceId
     * @return bool
     */

    public static function isOwner($resourceUserId) {
        $sessionUserId = Session::get('user');

        if($sessionUserId !== null && isset($sessionUserId['id'])) {
            $sessionUserId =  (int) $sessionUserId['id'];
            return $sessionUserId === $resourceUserId;
        }

        return false;
    }
}
//Authorization

        //if(Session::get('user')['id'] !== $listing->user_id) {
        //    $_SESSION['error_message'] = 'You are not authorized to delete this listing';
        //    return redirect('/listings/' . 
        //    $listing->id);
        //}