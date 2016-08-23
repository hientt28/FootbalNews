<?php

namespace App\Services;
use App\Models\Notification;
use App\Models\User;

trait Utils {

	public function getTotalNotification() 
	{
        $total = 0;   
        try {
        	if(auth()->user()->isAdmin()) {
            	$total = Notification::whereIn('status', ['0', '1'])->count();
        	} else {
        		$total = Notification::where('status', '=', '#')->count();
        	}
        } catch(Exception $e) {
            $total = 0;
        }

        return response()->json(['total' => $total, 'status' => 'OK']);
    }

    public function getListNotifications()
    {
		$notifications = collect([]);
		$datafields = [
			'id' => 'Id',
			'user_id' => 'User',
			'message' => 'Message',
			'status' => 'Status',
			'create_at' => 'Send at Time',
		];

		try {
			if(auth()->user()->isAdmin()) {
            	$data = Notification::whereIn('status', ['0', '1'])->get();
        	} else {
        		$data = Notification::where('status', '=', '#')->get();
        	}
			foreach ($data	as $key => $value) {
				$temp = [];
				$temp['id'] = $value->id;
				$temp['message'] = $value->message;
				$temp['status'] = $value->status;
				$temp['create_at'] = $value->created_at;
				$temp['user_id'] = $value->user_id;
				$temp['user_name'] = User::find($value->user_id)->name;
				$notifications->push($temp);
			}
		} catch (Exception $e) {
			return response()->json(['status' => 'error']);
		}

		return response()->json([
			'notifications' => $notifications,
			'datafields' => $datafields,
 			'status' => 'OK'
	 	]);
    }

}
