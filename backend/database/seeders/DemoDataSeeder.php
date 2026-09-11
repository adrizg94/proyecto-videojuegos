<?php

namespace Database\Seeders;

use App\Models\Creator;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $games = Game::all();
        $publishers = Publisher::all();
        $developers = Developer::all();
        $creators = Creator::all();

        $statuses = [
            'backlog',
            'playing',
            'completed',
            'paused',
            'dropped',
        ];

        $listTitles = [
            'My favorite games',
            'Games to play',
            'Best RPGs',
            'Indie gems',
            'Games with friends',
            'All-time classics',
            'Games to replay',
            'Hidden gems',
            'Weekend games',
            'Must play',
            'Games I recommend',
            'My childhood games',
            'Best stories',
            'Best soundtracks',
            'Best open worlds',
            'Best horror games',
            'Best action games',
            'Best adventure games',
            'Best shooters',
            'Best multiplayer games',
            'Best single-player games',
            'Best co-op games',
            'Relaxing games',
            'Hardest games',
            'Short but great',
            'Long games worth playing',
            'Games I need to finish',
            'Games I abandoned',
            'Games to buy',
            'Next games to play',
            'Games for rainy days',
            'Late night games',
            'Games to play on vacation',
            'My top 10',
            'Personal favorites',
            'Underrated games',
            'Overrated but fun',
            'Games everyone should try',
            'Greatest games ever',
            'Games that surprised me',
            'Games with amazing worlds',
            'Games with great characters',
            'Games with great combat',
            'Games with great atmosphere',
            'Games with memorable endings',
            'Games I would play again',
            'Games I could play forever',
            'Games worth completing 100%',
            'Games for achievement hunting',
            'Games to play someday',
            'Backlog priorities',
            'Currently obsessed with',
            'Games I cannot stop playing',
            'Favorite franchises',
            'Modern classics',
            'Old school favorites',
            'Best games of the 2000s',
            'Best games of the 2010s',
            'Best games of the 2020s',
            'Perfect weekend games',
            'Games for a long session',
            'Games for a quick session',
            'Story-driven favorites',
            'Gameplay over story',
            'Masterpieces',
            'Almost masterpieces',
            'Comfort games',
            'Games that deserve a sequel',
            'Games with the best bosses',
            'Games with great exploration',
            'Games with great progression',
            'Games I want to platinum',
            'Games I want to revisit',
            'Forgotten classics',
            'Cult classics',
            'My guilty pleasures',
            'Best Steam games',
            'Best console games',
            'Games to play with friends',
            'Games to play alone',
        ];

        $reviewTexts = [
            1 => [
                'I really tried to enjoy this game, but it just was not for me.',
                'A disappointing experience with too many problems.',
                'I expected much more from this game.',
                'Unfortunately, I cannot recommend this one.',
                'The idea was interesting, but the execution was very poor.',
                'I struggled to find anything that kept me interested.',
                'Too many issues made the experience frustrating.',
                'I gave it several chances, but it never clicked with me.',
                'The gameplay felt repetitive and unrewarding.',
                'I would not recommend this unless you are a huge fan of the genre.',
                'This was much more frustrating than enjoyable.',
                'I expected something better based on what I had heard.',
            ],

            2 => [
                'It has some good ideas, but the execution could be much better.',
                'There are enjoyable moments, but too many issues hold it back.',
                'Not terrible, but I would probably not play it again.',
                'It had potential, but ultimately disappointed me.',
                'Some parts are fun, but the overall experience feels unfinished.',
                'There are a few things I liked, but not enough to recommend it.',
                'The game has interesting concepts, but they are not used very well.',
                'It can be enjoyable in short sessions, but it gets repetitive quickly.',
                'There is something good here, but too many flaws get in the way.',
                'I wanted to like it more than I actually did.',
                'A mixed experience with more negatives than positives.',
                'It is playable, but I would only recommend it at a big discount.',
            ],

            3 => [
                'A solid game overall, although it has some noticeable flaws.',
                'I enjoyed it, but there are definitely things that could be improved.',
                'Worth playing if you enjoy this kind of game.',
                'A good experience, but nothing particularly memorable.',
                'It does most things well without really standing out.',
                'There are some great moments mixed with some weaker ones.',
                'A decent game that kept me entertained for most of the time.',
                'I liked it overall, even if some parts felt repetitive.',
                'Good enough to recommend, but do not expect anything groundbreaking.',
                'A balanced experience with both strengths and weaknesses.',
                'It was fun while it lasted, but I probably would not replay it.',
                'A respectable game that delivers what it promises.',
            ],

            4 => [
                'A really enjoyable game with only a few minor problems.',
                'I had a great time playing this and would definitely recommend it.',
                'Very good overall, with great gameplay and atmosphere.',
                'One of the better games I have played recently.',
                'A strong experience that kept me interested from start to finish.',
                'Great gameplay with only a few things that could be improved.',
                'I enjoyed almost every part of this game.',
                'Well worth playing and easy to recommend.',
                'A very polished experience with lots to enjoy.',
                'It does almost everything right.',
                'A memorable game that I would happily recommend to others.',
                'Really fun and engaging, even if it falls slightly short of being perfect.',
            ],

            5 => [
                'Absolutely fantastic. One of my favorite games.',
                'An amazing experience from beginning to end.',
                'Excellent gameplay, atmosphere and overall design.',
                'A masterpiece that I would happily play again.',
                'One of those games I wish I could experience for the first time again.',
                'Outstanding from start to finish.',
                'Everything about this game worked for me.',
                'An unforgettable experience and an easy recommendation.',
                'This is exactly what I look for in a video game.',
                'One of the best games I have played in years.',
                'Incredible gameplay, world and atmosphere.',
                'A fantastic game that deserves all the praise it gets.',
            ],
        ];

        $generalThreadTitles = [
            'What are you playing right now?',
            'What game surprised you the most?',
            'What is your favorite game of all time?',
            'Which game deserves a remake?',
            'What game are you most excited for?',
            'What game have you replayed the most?',
            'What is your favorite video game soundtrack?',
            'Which game has the best story?',
            'What game would you recommend to everyone?',
            'What is your favorite gaming memory?',
            'Which game disappointed you the most?',
            'What game deserves a sequel?',
            'What is the hardest game you have completed?',
            'Which game has the best open world?',
            'What game are you currently trying to finish?',
            'Physical or digital games?',
            'What game did you initially dislike but later enjoy?',
            'Which game has the best characters?',
            'What is your favorite indie game?',
            'What upcoming release are you waiting for?',
            'What game has the best combat system?',
            'Which game has the best atmosphere?',
            'What game would you erase from your memory to play again?',
            'What is the most underrated game you have played?',
            'What is the most overrated game you have played?',
            'Which game made you emotional?',
            'What was the first game you ever completed?',
            'Which game has your favorite protagonist?',
            'Which game has your favorite villain?',
            'What game took you the longest to finish?',
            'What game do you always come back to?',
            'Which game has the best side quests?',
            'What game has the best boss fights?',
            'What game has your favorite art style?',
            'Which franchise would you like to see return?',
            'What gaming trend would you like to disappear?',
            'What old game still holds up today?',
            'What game has aged poorly?',
            'What game deserves more attention?',
            'What is your favorite co-op game?',
            'What is your favorite multiplayer game?',
            'What is your favorite single-player experience?',
            'Which game has the best level design?',
            'What game has the best progression system?',
            'What is your favorite horror game?',
            'Which RPG would you recommend to a beginner?',
            'What game do you regret buying?',
            'What was your best gaming purchase?',
            'Do you prefer short games or long games?',
            'Do you usually finish the games you start?',
            'How big is your gaming backlog?',
            'Do you prefer story or gameplay?',
            'Do graphics matter to you?',
            'What game would make a great movie or series?',
            'What movie or series would make a great game?',
            'What is your favorite gaming console?',
            'What controller do you prefer?',
            'Do you still buy physical games?',
            'What game would you love to see remastered?',
            'What is the best game you played this year?',
        ];

        $generalPostBodies = [
            'I am curious to see what everyone thinks about this topic.',
            'I have been thinking about this lately and wanted to hear other opinions.',
            'There are so many different answers to this question, so I thought it could make an interesting discussion.',
            'I would love to know what other players think about this.',
            'This came up while talking with some friends and I wanted to ask the community.',
            'There are a lot of games that could fit here. What would you choose?',
            'I find this topic really interesting because everyone seems to have a different answer.',
            'Let me know your choices and why you picked them.',
            'I was looking through my game library and this question came to mind.',
            'I think everyone probably has a very different answer to this.',
            'There are so many possibilities that I cannot decide on just one.',
            'I have changed my opinion on this several times over the years.',
            'I thought this could be a fun topic to discuss.',
            'I am interested in discovering some games I may have missed.',
            'I want to hear some recommendations from the community.',
            'I have been debating this with a friend and we could not agree.',
            'I think personal nostalgia plays a big role here.',
            'I am sure there will be some controversial answers to this one.',
            'There is no correct answer, but I would like to hear everyone\'s opinion.',
            'I recently started thinking about how much my taste in games has changed.',
            'I am looking for something new to play, so your answers might help.',
            'This topic always leads to interesting conversations.',
            'I would especially like to hear why you chose your answer.',
            'Feel free to mention more than one game if you cannot choose.',
        ];

        $replyBodies = [
            'I completely agree with this.',
            'That is a really good choice.',
            'I had a very different experience with it.',
            'I have not played it yet, but it is definitely on my list.',
            'That was one of my favorites too.',
            'Interesting choice. I had not considered that one.',
            'I can understand why you feel that way.',
            'For me it was almost the opposite.',
            'I really need to give that game another chance.',
            'That is a great recommendation.',
            'I played it recently and really enjoyed it.',
            'I liked it, although there were a few things I would change.',
            'It is definitely one of the most memorable games I have played.',
            'I still need to finish that one.',
            'I would recommend it as well.',
            'That is probably one of the best examples.',
            'I had a lot of fun with it.',
            'I agree, especially because of the story and atmosphere.',
            'I think it is good, although maybe a little overrated.',
            'Great point. I had not thought about it that way.',
            'I feel exactly the same.',
            'I can see why so many people love it.',
            'That game never really clicked with me.',
            'I enjoyed it much more than I expected.',
            'I should probably go back and finish it.',
            'The gameplay was the best part for me.',
            'The story was what really made it memorable.',
            'I loved the soundtrack in that game.',
            'The atmosphere was incredible.',
            'I thought the ending was really good.',
            'The ending disappointed me a little.',
            'I would definitely play a sequel.',
            'I think a remake could work really well.',
            'It still holds up surprisingly well.',
            'I played it years ago and still remember it clearly.',
            'That is one of those games I wish I could experience again for the first time.',
            'I enjoyed it, but I would not call it one of my favorites.',
            'I think it depends a lot on what kind of games you normally enjoy.',
            'That one has been sitting in my backlog for ages.',
            'I bought it recently but have not started it yet.',
            'I was surprised by how much I liked it.',
            'I expected to love it, but it did not really work for me.',
            'Definitely underrated.',
            'I think it gets the recognition it deserves.',
            'I might be in the minority, but I did not enjoy it very much.',
            'That is probably in my top ten.',
            'Good choice, especially if you enjoy that genre.',
            'I would recommend playing it without looking up too much beforehand.',
            'The first few hours were slow, but it got much better later.',
            'I liked the first game more.',
            'I actually preferred the sequel.',
            'The world design was probably my favorite part.',
            'The combat took a while to click, but eventually I loved it.',
            'I had some issues with it, but overall I still enjoyed the experience.',
            'It is not perfect, but it does a lot of things really well.',
            'I completely forgot about that game.',
            'Now you are making me want to replay it.',
            'That brings back a lot of memories.',
            'I think that is a great example of a game that has aged well.',
            'I agree with most of what you said.',
        ];

        $gameThreadTitles = [
            'What do you think about %s?',
            'Is %s worth playing?',
            'Your thoughts on %s',
            'Favorite thing about %s?',
            'What would you change about %s?',
            'How would you rate %s?',
            'Best moment in %s?',
            'Is %s overrated or underrated?',
            'Would you recommend %s?',
            'Thoughts after finishing %s',
            'What is your favorite part of %s?',
            'What is the best thing about %s?',
            'What is the worst thing about %s?',
            'How has %s aged?',
            'Is %s still worth playing today?',
            'What rating would you give %s?',
            'What surprised you most about %s?',
            'What disappointed you most about %s?',
            'Did you finish %s?',
            'How long did it take you to finish %s?',
            'Would you replay %s?',
            'Does %s deserve a sequel?',
            'Does %s deserve a remake?',
            'What did you think of the ending of %s?',
            'Favorite character in %s?',
            'Favorite mission in %s?',
            'Favorite boss in %s?',
            'How difficult did you find %s?',
            'What platform did you play %s on?',
            'Any tips for someone starting %s?',
            'Is %s good for newcomers?',
            'What should I know before playing %s?',
            'Did %s meet your expectations?',
            'Was %s better than you expected?',
            'What makes %s special?',
            'What could %s have done better?',
            'Is the story of %s worth it?',
            'How good is the gameplay in %s?',
            'What do you think of the soundtrack in %s?',
            'What do you think of the world in %s?',
        ];

        $gamePostBodies = [
            'I recently started playing this and wanted to know what everyone else thinks.',
            'I would like to hear opinions from people who have already played it.',
            'I have been enjoying it so far. What was your experience?',
            'There seem to be very different opinions about this game.',
            'I am thinking about playing this soon. Would you recommend it?',
            'I finished it recently and wanted to discuss it with other players.',
            'What did you like the most about this game?',
            'I think this game has some really interesting ideas. What do you think?',
            'I have had this in my backlog for a while and I am thinking about finally starting it.',
            'I recently finished it and I am curious to compare my experience with other players.',
            'I had heard a lot about this game before playing it and I am interested in seeing whether it lived up to the hype for everyone else.',
            'I am only a few hours in, but so far I am really enjoying it.',
            'I am not completely convinced by it yet, so I wanted to hear some other opinions.',
            'This game seems to have a very dedicated fanbase. What do you like most about it?',
            'I have seen very mixed opinions about this one.',
            'I am considering buying it and would like to know if it is worth it.',
            'I played this a long time ago and I am considering replaying it.',
            'I have just reached the ending and I have a lot of thoughts about it.',
            'The gameplay has been really interesting so far. How did everyone else find it?',
            'I really like the atmosphere in this game.',
            'I am enjoying the story more than I expected.',
            'The game started slowly for me, but it has become much more interesting.',
            'I have been struggling with some parts of this game. Any advice?',
            'This is my first game from this franchise and I am curious what longtime fans think.',
            'I went into this without many expectations and it surprised me.',
            'I expected to enjoy this much more than I actually did.',
            'I think this game does some things extremely well but also has some obvious flaws.',
            'I would like to know whether people still recommend this game today.',
            'I am trying to decide whether to complete everything or just finish the main story.',
            'I am curious which parts of the game everyone remembers the most.',
        ];

        $gameThreadTitles = [
            'What do you think about %s?',
            'Is %s worth playing?',
            'Your thoughts on %s',
            'Favorite thing about %s?',
            'What would you change about %s?',
            'How would you rate %s?',
            'Best moment in %s?',
            'Is %s overrated or underrated?',
            'Would you recommend %s?',
            'Thoughts after finishing %s',
        ];

        $gamePostBodies = [
            'I recently started playing this and wanted to know what everyone else thinks.',
            'I would like to hear opinions from people who have already played it.',
            'I have been enjoying it so far. What was your experience?',
            'There seem to be very different opinions about this game.',
            'I am thinking about playing this soon. Would you recommend it?',
            'I finished it recently and wanted to discuss it with other players.',
            'What did you like the most about this game?',
            'I think this game has some really interesting ideas. What do you think?',
        ];

        $futureGames = $games->filter(function ($game) {
            return $game->release_date !== null
                && $game->release_date > now()->toDateString();
        });

        /*
        |--------------------------------------------------------------------------
        | General community threads
        |--------------------------------------------------------------------------
        */

        $generalTitles = collect($generalThreadTitles)
            ->shuffle()
            ->take(15);

        foreach ($generalTitles as $title) {
            $author = $users->random();

            $thread = $author->threads()->create([
                'game_id' => null,
                'title' => $title,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Initial post
            |--------------------------------------------------------------------------
            */

            $thread->posts()->create([
                'user_id' => $author->id,
                'body' => fake()->randomElement($generalPostBodies),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Replies
            |--------------------------------------------------------------------------
            */

            for ($i = 0; $i < rand(2, 8); $i++) {
                $replyAuthor = $users->random();

                $thread->posts()->create([
                    'user_id' => $replyAuthor->id,
                    'body' => fake()->randomElement($replyBodies),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Game community threads
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 25; $i++) {
            $author = $users->random();
            $game = $games->random();

            $titleTemplate = fake()->randomElement($gameThreadTitles);

            $thread = $author->threads()->create([
                'game_id' => $game->id,
                'title' => sprintf($titleTemplate, $game->name),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Initial post
            |--------------------------------------------------------------------------
            */

            $thread->posts()->create([
                'user_id' => $author->id,
                'body' => fake()->randomElement($gamePostBodies),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Replies
            |--------------------------------------------------------------------------
            */

            for ($j = 0; $j < rand(2, 8); $j++) {
                $replyAuthor = $users->random();

                $thread->posts()->create([
                    'user_id' => $replyAuthor->id,
                    'body' => fake()->randomElement($replyBodies),
                ]);
            }
        }

        foreach ($users as $user) {

            /*
            |--------------------------------------------------------------------------
            | Favorite games
            |--------------------------------------------------------------------------
            */

            $favoriteGames = $games
                ->random(rand(3, 10))
                ->pluck('id');

            $user->favoriteGames()->syncWithoutDetaching($favoriteGames);

            /*
            |--------------------------------------------------------------------------
            | Favorite publishers
            |--------------------------------------------------------------------------
            */

            $favoritePublishers = $publishers
                ->random(rand(1, 5))
                ->pluck('id');

            $user->publishers()->syncWithoutDetaching($favoritePublishers);

            /*
            |--------------------------------------------------------------------------
            | Favorite developers
            |--------------------------------------------------------------------------
            */

            $favoriteDevelopers = $developers
                ->random(rand(1, 5))
                ->pluck('id');

            $user->developers()->syncWithoutDetaching($favoriteDevelopers);

            /*
            |--------------------------------------------------------------------------
            | Favorite creators
            |--------------------------------------------------------------------------
            */

            $favoriteCreators = $creators
                ->random(rand(1, 5))
                ->pluck('id');

            $user->creators()->syncWithoutDetaching($favoriteCreators);

            /*
            |--------------------------------------------------------------------------
            | Game statuses
            |--------------------------------------------------------------------------
            */

            $statusGames = $games
                ->shuffle()
                ->take(rand(3, 10));

            foreach ($statusGames as $game) {
                $user->gameStatuses()->updateOrCreate(
                    [
                        'game_id' => $game->id,
                    ],
                    [
                        'status' => fake()->randomElement($statuses),
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Release alerts
            |--------------------------------------------------------------------------
            */

            if ($futureGames->isNotEmpty()) {
                $releaseAlertGames = $futureGames
                    ->shuffle()
                    ->take(rand(1, min(3, $futureGames->count())))
                    ->pluck('id');

                $user->releaseAlertGames()->syncWithoutDetaching($releaseAlertGames);
            }

            /*
            |--------------------------------------------------------------------------
            | Game lists
            |--------------------------------------------------------------------------
            */

            $userListTitles = collect($listTitles)
                ->shuffle()
                ->take(rand(1, 3));

            foreach ($userListTitles as $title) {
                $gameList = $user->gameLists()->firstOrCreate([
                    'title' => $title,
                ]);

                $listGames = $games
                    ->shuffle()
                    ->take(rand(3, 10))
                    ->pluck('id');

                $gameList->games()->syncWithoutDetaching($listGames);
            }

            /*
            |--------------------------------------------------------------------------
            | Reviews
            |--------------------------------------------------------------------------
            */

            $reviewGames = $games
                ->shuffle()
                ->take(rand(1, 5));

            foreach ($reviewGames as $game) {
                $rating = rand(1, 5);

                $user->reviews()->updateOrCreate(
                    [
                        'game_id' => $game->id,
                    ],
                    [
                        'rating' => $rating,
                        'review' => fake()->randomElement($reviewTexts[$rating]),
                    ]
                );
            }
        }
    }
}
