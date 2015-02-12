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

namespace F500\EventSourcing\Event;

use F500\EventSourcing\Metadata\Metadata;
use F500\EventSourcing\Timestamp\Timestamp;

/**
 * Class EventEnvelope
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class EventEnvelope implements Event
{
    /**
     * @var SerializableEvent
     */
    private $event;

    /**
     * @var int
     */
    private $playhead;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @var Timestamp
     */
    private $tookPlaceAt;

    /**
     * @param SerializableEvent $event
     * @param int               $playhead
     * @param Metadata          $metadata
     * @param Timestamp         $tookPlaceAt
     */
    public function __construct(SerializableEvent $event, $playhead, Metadata $metadata, Timestamp $tookPlaceAt)
    {
        $this->event       = $event;
        $this->playhead    = (int)$playhead;
        $this->metadata    = $metadata;
        $this->tookPlaceAt = $tookPlaceAt;
    }

    /**
     * @param SerializableEvent $event
     * @param int               $playhead
     * @return EventEnvelope
     */
    public static function wrap(SerializableEvent $event, $playhead)
    {
        return new EventEnvelope(
            $event,
            $playhead,
            new Metadata([]),
            Timestamp::now()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->event->aggregateId();
    }

    /**
     * @return SerializableEvent
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * @return int
     */
    public function playhead()
    {
        return $this->playhead;
    }

    /**
     * @return Metadata
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     * @return EventEnvelope
     */
    public function enrichMetadata(Metadata $metadata)
    {
        return new EventEnvelope(
            $this->event,
            $this->playhead,
            $this->metadata->merge($metadata),
            $this->tookPlaceAt
        );
    }

    /**
     * @return Timestamp
     */
    public function tookPlaceAt()
    {
        return $this->tookPlaceAt;
    }
}
