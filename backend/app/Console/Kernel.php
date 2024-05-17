<?php

namespace App\Console;

use App\Models\Design;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $reservations = Reservation::all(); // * Lista con los json de reservas
    
            foreach($reservations as $reservation){

                $today = date('Y/m/d');
                if($reservation->date == $today){
                    // Storage::disk('public')->append('output.json', $reservation);
                    $designs = Design::where('restaurant_id',$reservation->restaurant_id)->get();
                    foreach($designs as $design){
    
                        if($design->id == $reservation->design_id){
    
                            $tables = $design->tables;
                            $decoded_tables = json_decode($tables,true);
                            foreach($decoded_tables as $key => $table){

                                if($reservation->table_number == $decoded_tables[$key]['number']){

                                    $ocupated_hours = $decoded_tables[$key]['ocupated_hours']; // * Array de las horas
                             
                                    if(!in_array($reservation->hour,$ocupated_hours)){

                                        array_push($ocupated_hours,$reservation->hour);
                                        $decoded_tables[$key]['ocupated_hours'] = $ocupated_hours;
                                    }
                                }
                            }
                            $design->update([
                                'hall_name' => $design->hall_name,
                                'tables' =>  $decoded_tables,
                                'restaurant_id' => $design->restaurant_id
                            ]);
                        }
                    }
                }
            }
        })->everyThirtySeconds();

        $schedule->call(function () {

            $designs = Design::all();

            foreach($designs as $design){

                $tables = $design->tables;
                $decoded_tables = json_decode($tables,true);
                foreach($decoded_tables as $key => $table){

                    $decoded_tables[$key]['ocupated_hours'] = [];

                }
                $design->update([
                    'hall_name' => $design->hall_name,
                    'tables' =>  $decoded_tables,
                    'restaurant_id' => $design->restaurant_id
                ]);

            }


        })->dailyAt('00:00');


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
