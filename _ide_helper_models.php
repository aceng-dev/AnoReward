<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $project_name
 * @property string|null $description
 * @property string $status
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $developer_id
 * @property numeric $average_score
 * @property string $recommendation_status
 * @property string|null $manager_comments
 * @property numeric|null $proposed_bonus
 * @property \Illuminate\Support\Carbon|null $evaluated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $developer
 * @property mixed $amount
 * @property-read mixed $approved_at
 * @property-read mixed $period_month
 * @property mixed $reason
 * @property-read mixed $rejected_at
 * @property-read mixed $rejection_reason
 * @property mixed $status
 * @property-read \App\Models\User|null $manager
 * @property-read \App\Models\Task|null $task
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereAverageScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereEvaluatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereManagerComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereProposedBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereRecommendationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryRecommendation whereUpdatedAt($value)
 */
	class SalaryRecommendation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $developer_id
 * @property int $project_id
 * @property string $task_name
 * @property string $status
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int $pm_rating
 * @property int $calculated_score
 * @property string|null $ai_review
 * @property int|null $ai_suggested_rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $developer
 * @property-read mixed $deadline
 * @property-read mixed $title
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereAiReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereAiSuggestedRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCalculatedScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task wherePmRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTaskName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereUpdatedAt($value)
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property numeric|null $current_salary
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $total_points
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projectsAsManager
 * @property-read int|null $projects_as_manager_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalaryRecommendation> $salaryRecommendations
 * @property-read int|null $salary_recommendations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTotalPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

