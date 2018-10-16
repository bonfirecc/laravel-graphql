<?php namespace BonfireCC\GraphQL;

use BonfireCC\GraphQL\Console\InputMakeCommand;
use BonfireCC\GraphQL\Console\WhereInputMakeCommand;
use Illuminate\Support\ServiceProvider;
use BonfireCC\GraphQL\Console\MutationMakeCommand;
use BonfireCC\GraphQL\Console\QueryMakeCommand;
use BonfireCC\GraphQL\Console\TypeMakeCommand;
use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\DisableIntrospection;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;

class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootPublishes();

        $this->bootTypes();

        $this->bootSchemas();

        $this->bootRouter();
    }

    /**
     * Bootstrap router
     *
     * @return void
     */
    protected function bootRouter()
    {
        if(config('graphql.routes'))
        {
            include __DIR__.'/routes.php';
        }
    }

    /**
     * Bootstrap publishes
     *
     * @return void
     */
    protected function bootPublishes()
    {
        $configPath = __DIR__.'/../../config';

        $this->mergeConfigFrom($configPath.'/config.php', 'graphql');

        $this->publishes([
            $configPath.'/config.php' => config_path('graphql.php'),
        ], 'config');

        $viewsPath = __DIR__.'/../../resources/views';
        $this->loadViewsFrom($viewsPath, 'graphql');
    }

    /**
     * Bootstrap publishes
     *
     * @return void
     */
    protected function bootTypes()
    {
        $configTypes = config('graphql.types');
        foreach($configTypes as $name => $type)
        {
            if(is_numeric($name))
            {
                $this->app['graphql']->addType($type);
            }
            else
            {
                $this->app['graphql']->addType($type, $name);
            }
        }
    }

    /**
     * Add schemas from config
     *
     * @return void
     */
    protected function bootSchemas()
    {
        $configSchemas = config('graphql.schemas');
        foreach ($configSchemas as $name => $schema) {
            $this->app['graphql']->addSchema($name, $schema);
        }
    }

    /**
     * Configure security from config
     *
     * @return void
     */
    protected function applySecurityRules()
    {
        $maxQueryComplexity = config('graphql.security.query_max_complexity');
        if ($maxQueryComplexity !== null) {
            /** @var QueryComplexity $queryComplexity */
            $queryComplexity = DocumentValidator::getRule('QueryComplexity');
            $queryComplexity->setMaxQueryComplexity($maxQueryComplexity);
        }

        $maxQueryDepth = config('graphql.security.query_max_depth');
        if ($maxQueryDepth !== null) {
            /** @var QueryDepth $queryDepth */
            $queryDepth = DocumentValidator::getRule('QueryDepth');
            $queryDepth->setMaxQueryDepth($maxQueryDepth);
        }

        $disableIntrospection = config('graphql.security.disable_introspection');
        if ($disableIntrospection === true) {
            /** @var DisableIntrospection $disableIntrospection */
            $disableIntrospection = DocumentValidator::getRule('DisableIntrospection');
            $disableIntrospection->setEnabled(DisableIntrospection::ENABLED);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGraphQL();

        $this->registerConsole();
    }

    public function registerGraphQL()
    {
        $this->app->singleton('graphql', function($app)
        {
            $graphql = new GraphQL($app);

            $this->applySecurityRules();

            return $graphql;
        });
    }

    /**
     * Register console commands
     *
     * @return void
     */
    public function registerConsole()
    {
        $this->commands(TypeMakeCommand::class);
        $this->commands(QueryMakeCommand::class);
        $this->commands(MutationMakeCommand::class);
        $this->commands(InputMakeCommand::class);
        $this->commands(WhereInputMakeCommand::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['graphql'];
    }

}
