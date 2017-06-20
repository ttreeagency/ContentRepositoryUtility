<?php
namespace Ttree\ContentRepositoryUtility\NodeTypePostprocessor;

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Arrays;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;

/**
 * GenericNodeTypePostprocessor
 */
class GenericNodeTypePostprocessor implements NodeTypePostprocessorInterface
{

    /**
     * @var array
     * @Flow\InjectConfiguration(path="genericNodeTypePostprocessor")
     */
    protected $configuration;

    /**
     * Returns the processed Configuration
     *
     * @param NodeType $nodeType (uninitialized) The node type to process
     * @param array $configuration input configuration
     * @param array $options The processor options
     * @return void
     */
    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        if (!is_array($this->configuration)) {
            return;
        }
        foreach ($this->configuration as $country => $countryConfiguration) {
            if (!is_array($countryConfiguration['presets']) || !isset($countryConfiguration['mixins']) || !$this->isAtLeastOfOneType($nodeType, $countryConfiguration['mixins'])) {
                continue;
            }
            foreach ($countryConfiguration['presets'] as $presetName => $presetConfiguration) {
                $configuration = $this->applyPreset($nodeType, $configuration, $presetConfiguration['configuration']);
            }
        }
    }

    /**
     * @param NodeType $nodeType
     * @param array $nodeTypeNames
     * @return boolean
     */
    protected function isAtLeastOfOneType(NodeType $nodeType, array $nodeTypeNames)
    {
        foreach ($nodeTypeNames as $nodeTypeName => $nodeTypeStatus) {
            if ($nodeType->isOfType($nodeTypeName) && $nodeTypeStatus === TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param NodeType $nodeType
     * @param array $configuration
     * @param array $presetConfiguration
     * @return array
     */
    protected function applyPreset(NodeType $nodeType, array $configuration, array $presetConfiguration)
    {
        if (isset($presetConfiguration['mixins']) && is_array($presetConfiguration['mixins']) && !$this->isAtLeastOfOneType($nodeType, $presetConfiguration['mixins'])) {
            return $configuration;
        }
        return Arrays::arrayMergeRecursiveOverrule($configuration, $presetConfiguration);
    }

}
