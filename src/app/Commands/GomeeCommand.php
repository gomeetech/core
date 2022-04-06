<?php

namespace Gomee\Commands;

use Illuminate\Console\Command;

class GomeeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gomee
        {cmd=none : Lệnh}
        {package=none : Gói cài đặt}
        {--mode=flash : Chế độ}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cài đặt các gói của gomee';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $command = $this->argument('cmd');
        if($command == 'none'){
            $this->error('Vui lòng chọn lệnh! Ví dụ: install, update, remove, v.v..');
        }
        elseif($command == 'install'){
            $psckage = $this->argument('package');
            if($psckage == 'none'){
                $this->info('');
                $this->error('                             ');
                $this->error('  Bạn chưa chọn gói cài đặt  ');
                $this->error('                             ');
            }
            else{
                $this->info('');
                $a = strlen($psckage);
                $s = str_repeat(' ', $a);
                $this->error('                      ' . $s);
                $this->error('  Gói '.$psckage.' Không tồn tại  ');
                $this->error('                      ' . $s);
                $this->info('');
                
                $this->warn('Bạn có thể chạy lệnh sau để tài về trước khi cài đặt!');
                $this->info('');
                $this->info('   composer require gomeetech/'.$psckage);
                
            }
        }
        return 0;
    }
}
