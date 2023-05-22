<?php

namespace Database\Seeders;

use App\Models\Participation;
use App\Models\User;
use App\Models\UserPicture;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class userPictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // loop through each image in the public/images/submissions/ folder and add it to the database as a user_picture

        $userids = [5, 6, 7, 8];
        $competitionids = [3, 15];

        // create a participation for each user and competition
        foreach ($userids as $userid) {
            foreach ($competitionids as $competitionid) {
                DB::table('participation')->insert([
                    'user_id' => $userid,
                    'competition_id' => $competitionid,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'main_photo_id' => null,
                ]);
            }
        }

        $files = glob(public_path('images/submissions/*'));
        $firstSubmissionAmount = 10;
        $secondSubmissionAmount = 3;
        $thirdSubmissionAmount = 6;

        $userId = $userids[0];
        $competitionid = $competitionids[0];
        $lastKey = 0;
        $lastUserIdUsed = 0;

        foreach ($files as $key=>$file) {
            $fileName = basename($file);
            $fileDate = Carbon::now();
            // we create 10 submissions for user 5, 3 for user 6 and 2 for user 7

            if ($key < $firstSubmissionAmount) {
                $userId = $userids[0];
            } elseif ($key < $firstSubmissionAmount + $secondSubmissionAmount) {
                $userId = $userids[1];
            } elseif ($key < $firstSubmissionAmount + $secondSubmissionAmount + $thirdSubmissionAmount) {
                $userId = $userids[2];
                $lastKey = $key - 3;
                $lastUserIdUsed = $userId;
            } // else make a new participation with a different user
            else{
                // if there's only a certain amount of images left, we switch to the next competition
                if (count($files) - $key < 6) {
                    $competitionid = $competitionids[1];
                }
                // if it has been a random amount of keys between 2 and 4 since lastKey, then get a new user
                if ($key - $lastKey > rand(2, 4)) {
                    $lastKey = $key;
                    $userId = $lastUserIdUsed + 1;
                    $lastUserIdUsed = $userId + 1;
                    // check if that user exists
                    $user = User::where('id', $userId)->first();
                    if ($user == null) {
                        break;
                    }
                }
            }
            $participation = Participation::where('user_id', $userId)->where('competition_id', $competitionid)->first();
            if ($participation == null){
                DB::table('participation')->insert([
                    'user_id' => $userId,
                    'competition_id' => $competitionid,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'main_photo_id' => null,
                ]);
            }


            // get the participation, if the main picture is null, then set it to this picture
            $participation = Participation::where('user_id', $userId)->where('competition_id', $competitionid)->first();
            if ($participation->main_photo_id == null) {
                $participation->main_photo_id = $fileName;
                $participation->save();
            }

            DB::table('user_picture')->insert([
                'id' => $fileName,
                'submission_date' => $fileDate,
                'userId' => $userId,
                'competition_id' => $competitionid,
                'participation_id' => Participation::where('user_id', $userId)->where('competition_id', $competitionid)->first()->participation_id,

            ]);
            DB::table('homepage_banner_picture')->insert([
                'banner_id' => 'd0324272-f081-4c42-a20d-a84d3ac9f059.jpeg',
            ]);
        }







    }


}


