<?php

namespace App\Lib\Constant;

class Paper
{
    /** @var string 阅读 */
    const READING = "reading";

    /** @var string 写作 */
    const WRITING = "writing";

    /** @var string 听力 */
    const LISTENING = "listening";

    /** @var string 语言运用 */
    const LANGUAGE_USAGE = "language_usage";

    const TYPE = [Paper::READING, Paper::WRITING, Paper::LISTENING, Paper::LANGUAGE_USAGE];
}