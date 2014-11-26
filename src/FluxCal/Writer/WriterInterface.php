<?php

namespace FluxCal\Writer;

/**
 * Interface WriterInterface
 *
 * @author Timon F <dev@timonf.de>
 */
interface WriterInterface
{
    /**
     * Generates the wanted content.
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function write();
}