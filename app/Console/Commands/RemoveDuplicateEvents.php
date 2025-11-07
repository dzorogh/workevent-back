<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Industry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:remove-duplicates {industry_id=16} {--dry-run : Показать дубликаты без удаления}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Найти и удалить дублирующиеся мероприятия для указанной индустрии';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $industryId = (int) $this->argument('industry_id');
        $dryRun = $this->option('dry-run');

        $industry = Industry::find($industryId);
        if (!$industry) {
            $this->error("Индустрия с ID {$industryId} не найдена.");
            return 1;
        }

        $this->info("Поиск дубликатов для индустрии: {$industry->title} (ID: {$industryId})");

        // Ищем дубликаты по event_id в таблице event_industry
        // Находим event_id, которые встречаются несколько раз для данной индустрии
        $duplicateEventIds = DB::table('event_industry')
            ->where('industry_id', $industryId)
            ->select('event_id', DB::raw('COUNT(*) as count'))
            ->groupBy('event_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('event_id')
            ->toArray();

        if (empty($duplicateEventIds)) {
            $this->info("Дубликаты по ID не найдены.");
            return 0;
        }

        $this->info("Найдено событий с дублирующимися связями: " . count($duplicateEventIds));

        $toDelete = [];

        // Для каждого дублирующегося event_id получаем все записи из event_industry
        foreach ($duplicateEventIds as $eventId) {
            $event = Event::find($eventId);
            if (!$event) {
                continue;
            }

            // Получаем все записи из event_industry для этого event_id и industry_id
            $eventIndustryRecords = DB::table('event_industry')
                ->where('industry_id', $industryId)
                ->where('event_id', $eventId)
                ->orderBy('id')
                ->get();

            // Оставляем первую запись (с наименьшим ID), остальные удаляем
            $keepRecord = $eventIndustryRecords->first();
            $deleteRecords = $eventIndustryRecords->slice(1);

            $this->line("\n" . str_repeat('-', 80));
            $this->info("Дубликаты для события ID {$eventId}:");
            $this->line("  Название: " . $event->title);
            $this->line("  Дата начала: " . ($event->start_date ? $event->start_date->format('Y-m-d') : 'не указана'));
            $this->line("  Дата окончания: " . ($event->end_date ? $event->end_date->format('Y-m-d') : 'не указана'));

            $this->line("\n  Оставляем связь: ID записи {$keepRecord->id} (создано: {$keepRecord->created_at})");
            $this->warn("  Удаляем дублирующиеся связи:");
            foreach ($deleteRecords as $record) {
                $this->line("    - ID записи {$record->id} (создано: {$record->created_at})");
                $toDelete[] = $record->id;
            }
        }

        $this->line("\n" . str_repeat('=', 80));
        $this->info("Всего дубликатов для удаления: " . count($toDelete));

        if ($dryRun) {
            $this->warn("\nРежим DRY-RUN: дубликаты не будут удалены.");
            $this->info("Запустите команду без флага --dry-run для удаления.");
            return 0;
        }

        if (!$this->confirm("\nВы уверены, что хотите удалить " . count($toDelete) . " дубликатов?")) {
            $this->info("Операция отменена.");
            return 0;
        }

        // Удаляем дублирующиеся записи из event_industry
        $deleted = 0;
        DB::transaction(function () use ($toDelete, &$deleted) {
            foreach ($toDelete as $recordId) {
                DB::table('event_industry')
                    ->where('id', $recordId)
                    ->delete();
                $deleted++;
            }
        });

        $this->info("\nУспешно удалено дубликатов: {$deleted}");

        return 0;
    }

}

