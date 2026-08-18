<nav class="pagination" role="navigation">
    <?php if ($this->totalItemCount) : ?>

        <?php
        $getParams = $_GET;
        $results = $this->totalItemCount === 1 ? 'result' : 'results';
        ?>

        <span class="row-count">
            <?php echo sprintf($this->translate('Showing %1$s to %2$s of %3$s %4$s'), $this->firstItemNumber, $this->lastItemNumber, $this->totalItemCount, $results); ?>
        </span>

        <?php if ($this->pageCount > 1): ?>

            <div class="pager-wrapper">
                <!-- Prev Button -->
                <?php if ($this->current != 1): ?>
                    <?php $getParams['page'] = $previous; ?>
                    <a rel="prev" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>" aria-label="<?php echo __('Previous Page'); ?>" title="<?php echo __('Previous Page'); ?>" class="button prev"></a>
                <?php else: ?>
                    <span class="button prev disabled" disabled></span>
                <?php endif; ?>

                <!-- Pager -->
                <form class="pager" method="GET" action="">
                    <label>
                        <?php echo $this->translate('Page'); ?>
                        <input type="text" id="" name="page" class="page-input-top" value="<?php echo html_escape($this->current); ?>" size="4" <?php echo ($this->pageCount == 1) ? 'readonly' : ''; ?> aria-label="<?php echo $this->translate('Page'); ?>">
                    </label>
                    <span class="page-count"><?php echo sprintf($this->translate('of %s'), $this->pageCount); ?></span>
                    <input type="submit" class="gotopage-btn" value="Go">
                </form>

                <!-- Next Button -->
                <?php if ($this->current < $this->pageCount) : ?>
                    <?php $getParams['page'] = $next; ?>
                    <a rel="next" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>" aria-label="<?php echo __('Next Page'); ?>" title="<?php echo __('Next Page'); ?>" class="button next"></a>
                <?php else: ?>
                    <span class="button next disabled" disabled></span>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    <?php else: ?>
        <?php echo $this->translate('0 results'); ?>
    <?php endif; ?>
</nav>
