<?php

namespace App\Services;

use App\Models\Request as RequestModel;
use App\Services\HelperService;

class RequestService
{

    protected HelperService $helperService;

    public function __construct(
        HelperService $helperService
    ){
        $this->helperService = $helperService;
    }

    public function checkExistingRequests($userId, $courseId)
    {
        $pendingRequest = RequestModel::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'pending')
            ->first();

        $acceptedRequest = RequestModel::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'accepted')
            ->first();

        $rejectedRequest = RequestModel::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'rejected')
            ->first();

        return compact('pendingRequest', 'acceptedRequest', 'rejectedRequest');
    }

    public function createRequest($userId, $courseId)
    {
        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
        ];
        RequestModel::create($data);
    }

    public function redirectForRequest($status, $id)
    {
        if ($status['pendingRequest']) {
            return $this->helperService->returnWithInfo('student.one.course', 'Ваша заявка в обработке.', $id);
        }

        if ($status['rejectedRequest']) {
            return $this->helperService->returnWithInfo('student.one.course', 'Ваша заявка отклонена на этот курс.', $id);
        }

        if ($status['acceptedRequest']) {
            return $this->helperService->returnWithInfo('student.one.course', 'Ваша записи одобрена.', $id);
        }

        return null;
    }

    public function dataTransfer(){

    }
}
