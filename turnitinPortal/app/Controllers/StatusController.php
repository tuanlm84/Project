<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Job;

class StatusController extends Controller
{

    public function show($id)
    {
        file_put_contents('/var/www/html/debug/show.log', "SHOW STATUS: id=$id\n", FILE_APPEND);

        if (!$id || !is_numeric($id)) {
            echo "Thiếu hoặc sai ID bài nộp.";
            return;
        }

        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job) {
            echo "❌ Không tìm thấy bài nộp.";
            return;
        }

        View::render('status', ['title' => 'Trạng thái bài nộp', 'job' => $job]);
    }

}
