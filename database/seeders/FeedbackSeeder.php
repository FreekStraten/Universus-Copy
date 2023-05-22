<?php

namespace Database\Seeders;

use App\Models\Participation;
use App\Models\User;
use App\Models\UserPicture;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #region chatgpt generated feedback, 2 arrays positieve_feedback and negatieve_feedback

        $positieve_feedback = array(
            "Wow, wat een prachtige foto! De kleuren zijn zo levendig en de compositie is perfect.",
            "Deze foto is echt adembenemend. Ik kan er naar blijven kijken.",
            "Ik ben zo jaloers op je fotografietalent! Deze foto is gewoonweg geweldig.",
            "Ik vind het geweldig hoe je de natuurlijke schoonheid hebt vastgelegd in deze foto. Het is alsof ik er zelf ben.",
            "Deze foto heeft echt de 'wow'-factor. Je hebt oog voor detail en compositie.",
            "De emotie die je hebt vastgelegd in deze foto is geweldig. Het voelt als een momentopname van het leven.",
            "Dit is een van de beste foto's die ik ooit heb gezien. Ik ben sprakeloos.",
            "Ik ben zo onder de indruk van de manier waarop je het licht in deze foto hebt gevangen. Het is gewoon prachtig.",
            "Je hebt echt een talent voor het vastleggen van de essentie van een moment. Deze foto is daar het perfecte voorbeeld van.",
            "Deze foto is zo levendig en kleurrijk. Het doet me denken aan een prachtige zomerdag.",
            "Ik ben zo onder de indruk van de manier waarop je de emotie in deze foto hebt vastgelegd. Het is gewoon prachtig.",
        );

        $negatieve_feedback = array(
            "Helaas, deze foto is niet helemaal scherp. Misschien moet je wat meer oefenen met je camera-instellingen.",
            "Deze foto is niet erg interessant om naar te kijken. Misschien moet je een andere compositie proberen.",
            "Ik denk dat deze foto beter had kunnen zijn als je het licht beter had vastgelegd.",
            "Helaas is deze foto een beetje saai. Het mist diepgang en emotie.",
            "Ik vind deze foto een beetje te donker. Misschien moet je de belichting aanpassen.",
            "Deze foto is niet helemaal scherp en mist detail. Misschien moet je een statief gebruiken.",
            "Het onderwerp van deze foto is niet erg interessant. Misschien moet je op zoek gaan naar iets boeienders om te fotograferen.",
            "Ik denk dat de compositie van deze foto niet helemaal klopt. Het zou beter zijn als je het onderwerp anders had geplaatst.",
            "Helaas komt de kleurweergave van deze foto niet helemaal overeen met de werkelijkheid. Misschien moet je de witbalans aanpassen.",
            "Het is jammer dat deze foto niet helemaal scherp is en een beetje wazig oogt. Misschien moet je het diafragma aanpassen.",
        );

        #endregion

        $participationsToGenerateFeedBackFor = [1, 3, 4, 5, 11, 12];

        foreach($participationsToGenerateFeedBackFor as $key=>$participationId) {
            $participation = Participation::where('participation_id', $participationId)->first();
            if ($participation == null)
                continue;
            $userPicture = UserPicture::where('id', $participation->main_photo_id)->first();
            if ($userPicture == null)
                continue;
            // if it is the first loop, we get all users
            $users = User::where('id', '!=', $participation->user_id)->where('id', '!=', 1)->get();
            if ($key != 0) {
                // we get a random amount between 3 and 7
                $amountOfUsers = rand(3, 7);
                // we get a random amount of users from the users array
                $users = $users->random($amountOfUsers);
            }

            foreach ($users as $user) {
                if ($user->id == $participation->user_id)
                    continue;
                $stars = rand(1, 10);

                // we generate random feedback messages. If the stars is above 5, it's positive and below 5 is negative
                if ($stars > 5) {
                    $feedback = $positieve_feedback[rand(0, count($positieve_feedback) - 1)];
                } else {
                    $feedback = $negatieve_feedback[rand(0, count($negatieve_feedback) - 1)];
                }

                DB::table('user_has_picture_rating')->insert([
                    'user_id' => $user->id,
                    'picture_id' => $userPicture->id,
                    'participation_id' => $participation->participation_id,
                    'star_rating' => $stars,
                    'feedback' => $feedback,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);





            }
        }
    }
}
