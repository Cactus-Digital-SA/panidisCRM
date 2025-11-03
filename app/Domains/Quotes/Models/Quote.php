<?php

namespace App\Domains\Quotes\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Quotes\Enums\PaymentTermsEnum;
use App\Domains\Quotes\Enums\QuoteStatusEnum;
use App\Domains\Quotes\Enums\TaxRatesEnum;
use App\Models\CactusEntity;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class Quote extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string|null $uuid
     * @JMS\Serializer\Annotation\SerializedName("uuid")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $uuid = null;

    /**
     * @var string|null $referenceCode
     * @JMS\Serializer\Annotation\SerializedName("reference_code")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $referenceCode = null;

    /**
     * @var string|null $title
     * @JMS\Serializer\Annotation\SerializedName("title")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $title;

    /**
     * @var string $companyId
     * @JMS\Serializer\Annotation\SerializedName("company_id")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $companyId;

    /**
     * @var QuoteStatusEnum|null $status
     * @JMS\Serializer\Annotation\SerializedName("status")
     * @JMS\Serializer\Annotation\Type("enum<'App\Domains\Quotes\Enums\QuoteStatusEnum'>")
     */
    private ?QuoteStatusEnum $status;

    /**
     * @var DateTime|null $validUntil
     * @JMS\Serializer\Annotation\SerializedName("valid_until")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $validUntil;

    /**
     * @var PaymentTermsEnum|null $paymentTerms
     * @JMS\Serializer\Annotation\SerializedName("payment_terms")
     * @JMS\Serializer\Annotation\Type("enum<'App\Domains\Quotes\Enums\PaymentTermsEnum'>")
     */
    private ?PaymentTermsEnum $paymentTerms;

    /**
     * @var string|null $deliveryTerms
     * @JMS\Serializer\Annotation\SerializedName("delivery_terms")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $deliveryTerms;

    /**
     * @var float $subtotal
     * @JMS\Serializer\Annotation\SerializedName("subtotal")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $subtotal = 0;

    /**
     * @var float $totalDiscount
     * @JMS\Serializer\Annotation\SerializedName("total_discount")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $totalDiscount = 0;

    /**
     * @var TaxRatesEnum|null $taxRate
     * @JMS\Serializer\Annotation\SerializedName("tax_rate")
     * @JMS\Serializer\Annotation\Type("enum<'App\Domains\Quotes\Enums\TaxRatesEnum'>")
     */
    private ?TaxRatesEnum $taxRate = TaxRatesEnum::VAT_24;

    /**
     * @var float $tax
     * @JMS\Serializer\Annotation\SerializedName("tax")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $tax = 0;

    /**
     * @var float $total
     * @JMS\Serializer\Annotation\SerializedName("total")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $total = 0;

    /**
     * @var Company $company
     * @JMS\Serializer\Annotation\SerializedName("company")
     * @JMS\Serializer\Annotation\Type("App\Domains\Companies\Models\Company")
     */
    private Company $company;

    /**
     * @var QuoteItem[] $items
     * @JMS\Serializer\Annotation\SerializedName("items")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Quotes\Models\QuoteItem>")
     */
    private array $items = [];

    /**
     * @var User[] $contacts
     * @JMS\Serializer\Annotation\SerializedName("contacts")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\User>")
     */
    private array $contacts = [];

    /**
     * @var User[]|null $assignees
     * @JMS\Serializer\Annotation\SerializedName("assignees")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\User>")
     */
    private ?array $assignees = null;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Quote
    {
        $this->id = $id;
        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): Quote
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getReferenceCode(): ?string
    {
        return $this->referenceCode;
    }

    public function setReferenceCode(?string $referenceCode): Quote
    {
        $this->referenceCode = $referenceCode;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Quote
    {
        $this->title = $title;
        return $this;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function setCompanyId(string $companyId): Quote
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getStatus(): ?QuoteStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?QuoteStatusEnum $status): Quote
    {
        $this->status = $status;
        return $this;
    }

    public function setQuoteStatusAttribute(?string $value): ?Quote
    {
        $this->setStatus($value ? QuoteStatusEnum::from($value) : null);
        return $this;
    }

    public function getValidUntil(): ?DateTime
    {
        return $this->validUntil;
    }

    public function setValidUntil(?DateTime $validUntil): Quote
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    public function setPaymentTermsAttribute(?string $value): ?Quote
    {
        $this->setPaymentTerms($value ? PaymentTermsEnum::from($value) : null);
        return $this;
    }

    public function getPaymentTerms(): ?PaymentTermsEnum
    {
        return $this->paymentTerms;
    }

    public function setPaymentTerms(?PaymentTermsEnum $paymentTerms): Quote
    {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }

    public function getDeliveryTerms(): ?string
    {
        return $this->deliveryTerms;
    }

    public function setDeliveryTerms(?string $deliveryTerms): Quote
    {
        $this->deliveryTerms = $deliveryTerms;
        return $this;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): Quote
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    public function getTotalDiscount(): float
    {
        return $this->totalDiscount;
    }

    public function setTotalDiscount(float $totalDiscount): Quote
    {
        $this->totalDiscount = $totalDiscount;
        return $this;
    }

    public function setTaxRateAttribute(?string $value): ?Quote
    {
        $this->setTaxRate($value ? TaxRatesEnum::from($value) : null);
        return $this;
    }

    public function getTaxRate(): TaxRatesEnum
    {
        return $this->taxRate;
    }

    public function setTaxRate(?TaxRatesEnum $taxRate): Quote
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function setTax(float $tax): Quote
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): Quote
    {
        $this->total = $total;
        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): Quote
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return QuoteItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): Quote
    {
        $this->items = $items;
        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): Quote
    {
        $this->contacts = $contacts;
        return $this;
    }

    public function getAssignees(): ?array
    {
        return $this->assignees;
    }

    public function setAssignees(?array $assignees): Quote
    {
        $this->assignees = $assignees;
        return $this;
    }



    public static function fromRequest(Request $request): Quote
    {
        $quoteDTO = new Quote();

        return $quoteDTO
            ->setTitle($request['title'])
            ->setCompanyId($request['company_id'])
            ->setQuoteStatusAttribute($request['status'])
            ->setValidUntil($request['valid_until'] ? Carbon::parse($request['valid_until']) : null)
            ->setPaymentTermsAttribute($request['payment_terms'])
            ->setDeliveryTerms($request['delivery_terms'])
            ->setSubtotal($request['subtotal'])
            ->setTotalDiscount($request['total_discount'])
            ->setTaxRateAttribute($request['tax_rate'])
            ->setTax($request['tax'])
            ->setTotal($request['total']);
    }
}
