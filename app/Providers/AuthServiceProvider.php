<?php

namespace PacketPrep\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use PacketPrep\Policies\UserPolicy;
use PacketPrep\Policies\RolePolicy;
use PacketPrep\Policies\DocsPolicy;
use PacketPrep\User;
use PacketPrep\Models\User\Role;
use PacketPrep\Models\Content\Doc;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Doc::class => DocsPolicy::class,
        \PacketPrep\Models\Content\Chapter::class => \PacketPrep\Policies\ChapterPolicy::class,
        \PacketPrep\Models\System\Update::class => \PacketPrep\Policies\UpdatePolicy::class,
        \PacketPrep\Models\System\Finance::class => \PacketPrep\Policies\FinancePolicy::class,
        \PacketPrep\Models\System\Goal::class => \PacketPrep\Policies\GoalPolicy::class,
        \PacketPrep\Models\System\Report::class => \PacketPrep\Policies\ReportPolicy::class,
        \PacketPrep\Models\Social\Blog::class => \PacketPrep\Policies\BlogPolicy::class,
        \PacketPrep\Models\Social\Social::class => \PacketPrep\Policies\SocialPolicy::class,

        \PacketPrep\Models\Dataentry\Project::class => \PacketPrep\Policies\Dataentry\ProjectPolicy::class,
        \PacketPrep\Models\Dataentry\Category::class => \PacketPrep\Policies\Dataentry\CategoryPolicy::class,
        \PacketPrep\Models\Dataentry\Tag::class => \PacketPrep\Policies\Dataentry\TagPolicy::class,
        \PacketPrep\Models\Dataentry\Passage::class => \PacketPrep\Policies\Dataentry\PassagePolicy::class,
        \PacketPrep\Models\Dataentry\Question::class => \PacketPrep\Policies\Dataentry\QuestionPolicy::class,

        \PacketPrep\Models\Recruit\Job::class => \PacketPrep\Policies\Recruit\JobPolicy::class,
        \PacketPrep\Models\Recruit\Form::class => \PacketPrep\Policies\Recruit\FormPolicy::class,

        \PacketPrep\Models\Library\Repository::class => \PacketPrep\Policies\Library\RepositoryPolicy::class,
        \PacketPrep\Models\Library\Structure::class => \PacketPrep\Policies\Library\StructurePolicy::class,
        \PacketPrep\Models\Library\Ltag::class => \PacketPrep\Policies\Library\LtagPolicy::class,
        \PacketPrep\Models\Library\Lpassage::class => \PacketPrep\Policies\Library\LpassagePolicy::class,
        \PacketPrep\Models\Library\Lquestion::class => \PacketPrep\Policies\Library\LquestionPolicy::class,
        \PacketPrep\Models\Library\Version::class => \PacketPrep\Policies\Library\VersionPolicy::class,
        \PacketPrep\Models\Library\Video::class => \PacketPrep\Policies\Library\VideoPolicy::class,
        \PacketPrep\Models\Library\Document::class => \PacketPrep\Policies\Library\DocumentPolicy::class,

        \PacketPrep\Models\Course\Course::class => \PacketPrep\Policies\Course\CoursePolicy::class,
        \PacketPrep\Models\Course\Index::class => \PacketPrep\Policies\Course\IndexPolicy::class,


        \PacketPrep\Models\Product\Client::class => \PacketPrep\Policies\Product\ClientPolicy::class,
        \PacketPrep\Models\Product\Client::class => \PacketPrep\Policies\Product\AdminPolicy::class,

        \PacketPrep\Models\Exam\Exam::class => \PacketPrep\Policies\Exam\ExamPolicy::class,
        \PacketPrep\Models\Exam\Section::class => \PacketPrep\Policies\Exam\SectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
