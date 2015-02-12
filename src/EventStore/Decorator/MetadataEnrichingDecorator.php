<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * For more information, please view the LICENSE file that was distributed with
 * this source code.
 */

namespace F500\EventSourcing\EventStore\Decorator;

use F500\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use F500\EventSourcing\Collection\EventEnvelopeStream;
use F500\EventSourcing\Event\EventEnvelope;
use F500\EventSourcing\EventStore\StoresEvents;
use F500\EventSourcing\Exception\CollectionIsEmpty;
use F500\EventSourcing\Exception\InvalidItemInCollection;
use F500\EventSourcing\Metadata\EnrichesMetadata;

/**
 * Class MetadataEnrichingDecorator
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class MetadataEnrichingDecorator implements StoresEvents
{
    /**
     * @var EnrichesMetadata[]
     */
    private $metadataEnrichers;

    /**
     * @var StoresEvents
     */
    private $next;

    /**
     * @param EnrichesMetadata[] $metadataEnrichers
     * @param StoresEvents       $next
     */
    public function __construct(array $metadataEnrichers, StoresEvents $next)
    {
        foreach ($metadataEnrichers as $metadataEnricher) {
            $this->guardMetadataEnricherType($metadataEnricher);

            $this->metadataEnrichers[] = $metadataEnricher;
        }

        $this->guardAmountOfMetadataEnrichers();

        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(EventEnvelopeStream $envelopeStream)
    {
        $enrichedEventEnvelopes = [];

        /** @var EventEnvelope $eventEnvelope */
        foreach ($envelopeStream as $eventEnvelope) {
            /** @var EnrichesMetadata $metadataEnricher */
            foreach ($this->metadataEnrichers as $metadataEnricher) {
                $eventEnvelope = $metadataEnricher->enrich($eventEnvelope);
            }

            $enrichedEventEnvelopes[] = $eventEnvelope;
        }

        $this->next->commit(new EventEnvelopeStream($enrichedEventEnvelopes));
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        return $this->next->get($aggregateId);
    }

    /**
     * @param mixed $metadataEnricher
     * @throws InvalidItemInCollection
     */
    private function guardMetadataEnricherType($metadataEnricher)
    {
        if (!($metadataEnricher instanceof EnrichesMetadata)) {
            throw InvalidItemInCollection::create($metadataEnricher, 'F500\EventSourcing\Metadata\EnrichesMetadata');
        }
    }

    /**
     * @throws CollectionIsEmpty
     */
    private function guardAmountOfMetadataEnrichers()
    {
        if (!$this->metadataEnrichers) {
            throw CollectionIsEmpty::create();
        }
    }
}
