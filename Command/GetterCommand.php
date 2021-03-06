<?php

namespace Inwebo\Favicon\Command;

use Inwebo\Favicon\Model\FaviconBuilder;
use Inwebo\Favicon\Model\Queries\AppleTouchIconQuery;
use Inwebo\Favicon\Model\Queries\AppleTouchPrecomposedQuery;
use Inwebo\Favicon\Model\Queries\IconQuery;
use Inwebo\Favicon\Model\Queries\ShortcutIconQuery;
use Inwebo\Favicon\Model\Queries\SvgQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetterCommand extends Command
{
    protected static $defaultName = 'import:favicon';

    protected function configure()
    {
        $this
            ->setAliases(['if'])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonData = [];

        $iterator = new \GlobIterator('input/*.json');
        $output->writeln(sprintf('<info>Reading %s json files</info>', $iterator->count()));

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $urls = json_decode(file_get_contents($file->getRealPath()));

            foreach ($urls as $favorites) {
                $url = $favorites->href;

//                $output->writeln(sprintf('Getting %s', $url));

                /**
                 * Fail to reach $url
                 * @see https://stackoverflow.com/a/1133973
                 */
                $orig = error_reporting();
                error_reporting(0);
                $headers = get_headers($url);
                error_reporting($orig);

                if (false === $headers) {
                    $output->writeln(sprintf('%s <error>✗</error>', $url));
                    $output->writeln('');
                } else {
                    try {
                        $output->writeln(sprintf('%s <info>✔</info>', $url));

                        $faviconBuilder = new FaviconBuilder($url, $output);

                        $faviconBuilder->getFinder()->getQueries()->attach(new IconQuery());
                        $faviconBuilder->getFinder()->getQueries()->attach(new ShortcutIconQuery());
                        $faviconBuilder->getFinder()->getQueries()->attach(new AppleTouchIconQuery());
                        $faviconBuilder->getFinder()->getQueries()->attach(new AppleTouchPrecomposedQuery());
                        $faviconBuilder->getFinder()->getQueries()->attach(new SvgQuery());

                        $favicons = $faviconBuilder->build();
                        $output->writeln('');

                        $jsonData = array_merge($jsonData, $favicons);
                    } catch (\Exception $e) {
                        $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
                    }
                }
            }
        }

        $output->writeln(sprintf('Writing %s icon to json file', count($jsonData)));

        file_put_contents('output/data.json', json_encode($jsonData, JSON_PRETTY_PRINT));
        $output->writeln('Done ');

        return Command::SUCCESS;
    }
}
