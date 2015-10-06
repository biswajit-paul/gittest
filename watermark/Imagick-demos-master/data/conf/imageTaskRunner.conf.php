<?php

$config = <<< END


[program:imageTaskRunner]
directory=${'imagick.root.directory'}
command=php bin/cli.php imageRunner
process_name=%(program_name)s_%(process_num)d
numprocs=2
autostart=true
autorestart=true
user=imagickdemos
stdout_logfile=${'php.log.directory'}/imageTaskRunner_stdout.log
stdout_logfile_maxbytes=1MB
stderr_logfile=${'php.log.directory'}/imageTaskRunner_stderr.log
stderr_logfile_maxbytes=1MB
log_stdout=true             ; if true, log program stdout (default true)
log_stderr=true             ; if true, log program stderr (def false)



END;

return $config;

