<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/about.js"></script>';
$extra_css = '';
ob_start();
?>

<div class="row" style="position: absolute; top: 10vh;">
    <div class="col-12 p-0 m-0">
        <img src="<?= BASE_URL . '/img/common/banner4.jpg' ?>" alt="" style="width: 100vw; height: 90vh; object-fit: cover;">
    </div>
    <div class="col-8 p-5 m-0" style="background-color:var(--background_translucent);position:absolute;right:0;width: 66vw; height: 90vh;">
        <h2 style="font-size:14vh;">welcome</h2>
        <h2 style="font-size:4vh;">to a relaxing stay <br> at our humble abode</h2>
        <h2 style="font-size:3vh; text-align: justify; text-justify: inter-word;" class="pt-5">
            We at King Garden View conduct business in a sustainable manner
            and part of our focus remains on the experience of our guests and another
            important faculty is dedicated to being good citizens and to a great extent,
            ensuring our team's safety and mental wellbeing. We aim to engage in sustainable
            policies that aid and benefit both locals and guests in the hotel and in their lives.
        </h2>
        <div class="btn-group-vertical">
            <button type="button" class="clear_btn" data-bs-toggle="modal" data-bs-target="#Sustainability">
                <h2 style="font-size:2vh;" class="pt-5">• View Sustainability Policy</h2>
            </button>
            <button type="button" class="clear_btn" data-bs-toggle="modal" data-bs-target="#Food">
                <h2 style="font-size:2vh;">• View Food Safety Policy</h2>
            </button>
            <button type="button" class="clear_btn" data-bs-toggle="modal" data-bs-target="#Environmental">
                <h2 style="font-size:2vh;">• View Environmental Policy</h2>
            </button>
            <button type="button" class="clear_btn" data-bs-toggle="modal" data-bs-target="#Child">
                <h2 style="font-size:2vh;">• View Child Protection Policy</h2>
            </button>
        </div>
    </div>
</div>

<!-- Book Now Button -->
<div id="booknow">
    <div class="success-btn" id="book_btn">Book Now!</div>
</div>

<!-- Sustainability Modal -->
<div class="modal fade" id="Sustainability" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Sustainability Policy</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                Cinnamon Grand will strive to conduct its activities in accordance with the highest standards of corporate
                best practice and in compliance with all applicable local and international regulatory requirements and
                conventions.<br>
                We are monitoring and assessing the quality and environmental impact of its operations, services and
                products whilst striving to include its supply chain partners and customers, where relevant and to the
                extent possible.<br>
                Cinnamon Grand is committed to transparency and open communication about its environmental and
                social practices in addition to its economic performance. It seeks dialogue with its stakeholders to
                contribute to the development of global best practice, while promoting the same commitment to
                transparency and open communication from its partners and customers.<br>
                We strive to be an employer of choice by providing a safe, secure and nondiscriminatory working
                environment for its employees whose rights are fully safeguarded and who can have equal opportunity
                to realize their full potential. We will abide by national laws and wherever possible will strive to emulate
                global best practice governing the respective industry groups, seeking continuous improvement of health
                and safety in the workplace.<br>
                We will promote good relationships with all communities of which we are a part and enhance their quality
                of life and opportunities while respecting peoples culture, ways of life and heritage.<br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Food Safety Modal -->
<div class="modal fade" id="Food" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Food Safety Policy</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                “Cinnamon Grand Colombo” will take required steps to ensure that her products are safe and fit
                for human consumption.<br>
                The policy of Food Safety extends to all related activities ranging from supply of ingredients,
                packaging, acceptance, storage, distribution, each stage of cooking, product use, disposal
                external service providers and contractors.<br>
                We will achieve this by,<br>
                • Design, Operate, maintain kitchen layout to meet product safety criteria.<br>
                • Establishing and maintaining standards and procedures to monitor and manage all Critical
                Control Points in the food preparations and catering operations.<br>
                • Build a food safety capability mindset and culture through structured programmes that
                develop employees competencies and technical skills, increase awareness, manage risk
                and drive increasing levels of excellence across the hotel.<br>
                • Establishing procedures to identify and eliminate of any harmful products unfit for human
                consumption.<br>
                • Protecting food products from potential hazards caused by physical, microbiological
                chemical and allergy contamination adhering through Hazard Analysis Critical Control
                Points (HACCP).<br>
                • Adhering to ISO 22000: 2018 principles to satisfy applicable food safety requirements,
                including statutory and regulatory requirements framed under the Food Act (No. 26 of
                1980) and mutually agreed customer requirements related to food safety.<br>
                • Develop the awareness of this policy within our employees, direct suppliers, customers,
                outsourced service providers and contractors.<br>
                • Develop and strive to continually improve our processes capable of providing safe food
                products through an efficient, effective and suitable food safety management system.<br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Environmental Modal -->
<div class="modal fade" id="Environmental" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Environmental Policy</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                “Cinnamon Grand Colombo” stand committed to continuously provide services surpassing the
                needs and expectations of her customers. During our operations, we strive to protect, preserve
                and nurture the environment on which our hotels activities have a significant impact.<br>
                We aim continually to reduce the usage of Water, Energy, Production and Packaging Materials
                used in our operations to recycle and add value to waste. We also aim to continuously reduce
                operational activities including prevention of pollution of water, air, noise, heat, light, land and
                wild life which has a significant impact to the environment and other specific commitments
                relevant to the context of the organization.<br>
                In order to achieve this objective “Cinnamon Grand Colombo” is committed to educate, train and
                motivate employees and all our business associates including external service providers and
                suppliers to enhance awareness of the importance in integrating environmental protection into
                their daily routine. We ensure compliance obligation as environmental regulation and legislature
                affecting our hotel is considered and met when environmental plans and procedures are
                developed.<br>
                We have developed and implemented an Environmental Management System to meet ISO
                14001:2015 International Standard requirements to ensure our continuous commitment to
                preserve and protect the environment for the benefit of future generations.<br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Child Protection Modal -->
<div class="modal fade" id="Child" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Child Protection Policy</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                The Cinnamon Grand shall monitor all matters that concern the safety and protection of children
                and their rights.<br>
                We are aware that sexual exploitation and other forms of child abuse can occur in the tourism
                industry. Therefore, our hotel is committed to taking reasonable steps to protect the children
                within our premises.<br>
                We are aware that children up to the age of 18 may be subject to many forms of abuse and
                exploitation including but not limited to:<br>
                Physical and verbal abuse from family members/guardians, other guests, employees or visitors,
                Confinement, being locked alone in a room for periods of time, Abandonment, being left on the
                premises without proper supervision, undertaking work meant for adults or without special
                conditions to protect them, Pornography, Trafficking, Sexual abuse and Prostitution
                In delivering this commitment, Cinnamon Grand Colombo will endeavor to:<br>
                • Make sure that all our employees understand why safeguarding the rights of children is
                important and how it is every employees responsibility to take reasonable steps to protect
                children from harm within our premises.<br>
                • Ensure children are not employed by us to undertake inappropriate work normally
                undertaken by adults<br>
                • Train our staff to identify and act accordingly when there is suspicion of a situation where
                child abuse or exploitation might occur<br>
                • Make sure to identify and report any incidents to the relevant local enforcement authority.<br>
                • Have a zero-tolerance policy regarding child pornography, trafficking, sexual abuse or
                prostitution in our hotel<br>
                • This information is communicated to all employees<br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>