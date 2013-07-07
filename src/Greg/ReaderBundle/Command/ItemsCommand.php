<?php

/**
 * Description of Items
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Command;

//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Greg\ReaderBundle\Rss\RssParser;

class ItemsCommand extends ContainerAwareCommand 
{

    protected function configure() {
        $this->setName('reader:getitems')
                ->setDescription('items')
                ->addArgument(
                        'nb', 
                        InputArgument::OPTIONAL, 
                        'Nombre d\'item à parser'
                )
                ->addOption(
                        'yell', 
                        NULL, 
                        InputOption::VALUE_NONE, 
                        'If set, the task will yell'
        );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nb = $input->getArgument('nb');
        if (!$nb) {
            $nb = 10;
        }
        
        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $rss = $this->container->get('rss_parser');
        $text = $rss->parser($nb);
        
        $text .= "\n$nb éléments par channel.";
        
        $output->writeln($text);
    }
}

?>
