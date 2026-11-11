<?php

namespace App\Console\Commands;

use App\Enums\AnswerOptionEnum;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\StudentAnswerOption;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SynchronizationExamQuestionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:synchronization-exam-question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration...');
        $this->newLine();

        $totalQuestions = ExamQuestion::whereNotNull('answer')->count();

        if ($totalQuestions === 0) {
            $this->warn('No exam questions found with answers. Nothing to migrate.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalQuestions} exam questions to process.");

        $totalOptionsInserted = 0;
        $totalStudentAnswersInserted = 0;
        $chunkSize = 500;

        $progressBar = $this->output->createProgressBar($totalQuestions);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %message%');
        $progressBar->setMessage('Processing...');
        $progressBar->start();

        try {
            DB::transaction(function () use ($chunkSize, $progressBar, &$totalOptionsInserted, &$totalStudentAnswersInserted) {
                $now = now();

                ExamQuestion::with(['studentQuestionAnswers' => function ($query) {
                    $query->whereNotNull('answer');
                }])
                    ->whereNotNull('answer')
                    ->chunkById($chunkSize, function ($examQuestions) use ($now, $progressBar, &$totalOptionsInserted, &$totalStudentAnswersInserted) {

                        $examQuestionOptions = [];
                        $studentAnswerOptions = [];

                        foreach ($examQuestions as $examQuestion) {
                            $correctAnswer = $examQuestion->answer;

                            foreach (AnswerOptionEnum::arrayCases() as $option) {
                                $key = $examQuestion->id . '-' . $option;
                                $examQuestionOptions[$key] = [
                                    'id'                => (string) Str::uuid(),
                                    'exam_question_id'  => $examQuestion->id,
                                    'option_label'      => $option,
                                    'option_text'       => $examQuestion->$option,
                                    'is_correct'        => $correctAnswer == $option,
                                    'created_at'        => $now,
                                    'updated_at'        => $now,
                                ];
                            }

                            foreach ($examQuestion->studentQuestionAnswers as $studentQuestionAnswer) {
                                $key = $examQuestion->id . '-' . $studentQuestionAnswer->answer;

                                if (!isset($examQuestionOptions[$key])) {
                                    throw new \RuntimeException(
                                        "Exam question option not found for key [{$key}]. " .
                                        "Student answer [{$studentQuestionAnswer->answer}] may be invalid."
                                    );
                                }

                                $studentAnswerOptions[] = [
                                    'id'                         => (string) Str::uuid(),
                                    'student_question_answer_id' => $studentQuestionAnswer->id,
                                    'exam_question_option_id'    => $examQuestionOptions[$key]['id'],
                                    'created_at'                 => $now,
                                    'updated_at'                 => $now,
                                ];
                            }
                        }

                        foreach (array_chunk(array_values($examQuestionOptions), 200) as $batch) {
                            ExamQuestionOption::insert($batch);
                            $totalOptionsInserted += count($batch);
                        }

                        foreach (array_chunk($studentAnswerOptions, 200) as $batch) {
                            StudentAnswerOption::insert($batch);
                            $totalStudentAnswersInserted += count($batch);
                        }

                        $progressBar->advance($examQuestions->count());
                        $progressBar->setMessage("Options: {$totalOptionsInserted} | Student Answers: {$totalStudentAnswersInserted}");
                    });
            });

            $progressBar->setMessage('Done!');
            $progressBar->finish();

            $this->newLine(2);
            $this->info('✅ Migration completed successfully!');
            $this->table(
                ['Description', 'Total Inserted'],
                [
                    ['Exam Question Options', number_format($totalOptionsInserted)],
                    ['Student Answer Options', number_format($totalStudentAnswersInserted)],
                ]
            );

            return Command::SUCCESS;

        } catch (\RuntimeException $e) {
            $progressBar->finish();
            $this->newLine(2);
            $this->error('❌ Migration failed due to data inconsistency:');
            $this->error('   ' . $e->getMessage());
            $this->warn('All inserted data has been rolled back. No changes were saved.');

            return Command::FAILURE;

        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine(2);
            $this->error('❌ Migration failed due to an unexpected error:');
            $this->error('   ' . $e->getMessage());
            $this->warn('All inserted data has been rolled back. No changes were saved.');

            Log::error('Migration failed', [
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
