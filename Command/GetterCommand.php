<?php

namespace Inwebo\Favicon\Command;

use Inwebo\Favicon\Model\Client;
use Inwebo\Favicon\Model\Strategies\DefaultStrategy;
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
        $output->writeln(sprintf('Reading %s json files', $iterator->count()),OutputInterface::OUTPUT_RAW);

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $urls = json_decode(file_get_contents($file->getRealPath()));

            foreach ($urls as $favorites) {
                $url = $favorites->href;

                /**
                 * Fail to reach $url
                 * @see https://stackoverflow.com/a/1133973
                 */
                $orig = error_reporting();
                error_reporting(0);
                $headers = get_headers($url);
                error_reporting($orig);

                if (false === $headers) {
                    continue;
                } else {
                    try  {
                        $client     = new Client($url);
                        $strategies = new \SplObjectStorage();
                        $strategies->attach(new DefaultStrategy($client->getDocument()));

                        $client
                            ->setStrategies($strategies)
                            ->execute()
                        ;


                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }
                }
            }
        }

        $output->writeln(sprintf('Writing %s icon to json file', count($jsonData)));

        file_put_contents('output/data.json', json_encode($jsonData));
        $output->writeln('Done ');

        return Command::SUCCESS;
    }
}
