<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('strip-templates')
    ->setDefinition(array(
         new InputArgument('directory', InputArgument::OPTIONAL, 'Some help', __DIR__),
    ))
    ->setDescription('My command description')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $directory = $input->getArgument('directory');

        $finder = new \Symfony\Component\Finder\Finder();

        $iterator = $finder
            ->files()
            ->name('*.html')
            ->depth(1)
            ->in($directory);
        $formatter = new \Symfony\Component\Console\Helper\FormatterHelper();
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($iterator as $file) {
            /** @var \QueryPath\DOMQuery $qp */
            $qp = QueryPath::withHTML($file->getContents(), 'body');
            if ($qp) {
                $contents = $qp->html();
                ladybug_dump($contents);
                if (!$contents) {
                    ladybug_dump($file->getRelativePath());
                }
            }
        }
    })
;

return $console;
