<template>
  <v-layout row wrap>
    <v-flex shrink md12>
      <v-img :aspect-ratio="16/2" src="/blog/img/slider/slider.png" class="pt-5">
        <v-layout row wrap justify-center>
          <v-flex md10 class="text-md-center">
            <span class="title text-uppercase font-weight-black">Chính Sách</span>
          </v-flex>
          <v-flex md10 class="text-md-center">
            <router-link :to="{name:'home'}">Trang Chủ</router-link>&nbsp;/&nbsp;Chính Sách
          </v-flex>
        </v-layout>
      </v-img>
    </v-flex>
    <v-flex shrink md3 class="pa-2 subheading">
      <div>
        <span class="title font-weight-bold">T</span>rước khi thực hiện một yêu cầu dịch vụ cần xem rõ các điều khoản và nội dung chính sách liên quan.
        Mọi thắc mắc có thể liên hệ với hệ thống hoặc nhà cung cấp chỗ ở mà khách hàng đang chọn sử dụng dịch
        vụ qua email, số điện thoại,…
      </div>
      <div>
        <span class="title font-weight-bold">V</span>iệc thanh toán được thực hiện an toàn từ thẻ tín dụng/ghi nợ hoặc tài khoản ngân hàng của bạn
        đến tài khoản ngân hàng của nhà cung cấp chỗ ở thông qua đơn vị xử lý thanh toán thứ ba.
      </div>
      <div>
        <span class="title font-weight-bold">G</span>iá hiển thị trên hệ thống là giá đã bao gồm thuế giá trị gia tăng và các loại phí khác. Tuy nhiên,
        những mức giá được đưa ra bởi những nhà cung cấp chỗ ở này có thể bao gồm các điểm hạn chế và điều
        kiện như: không thể hủy và không hoàn tiền. Vui lòng kiểm tra các chi tiết và điều kiện về sản phẩm,
        dịch vụ và đặt phòng một cách thấu đáo để được biết về các điều kiện này trước khi thực hiện đặt phòng.
      </div>
    </v-flex>
    <v-flex shrink md6 class="pa-2 subheading">
      <div>
        <span class="title font-weight-bold">H</span>ủy đặt phòng qua việc thực hiện đặt phòng với một nhà cung cấp chỗ ở, bạn chấp nhận và đồng
        ý chính sách hủy và vắng mặt tương ứng của nhà cung cấp chỗ ở. Xin lưu ý rằng một số giá, phí
        hoặc ưu đãi đặc biệt không thể được hủy bỏ, hoàn tiền hoặc thay đổi.
      </div>
      <div>
        <span class="title font-weight-bold">N</span>hà cung cấp chỗ ở vẫn có thể thu thuế thành phố/thuế du lịch trong trường hợp vắng mặt hoặc hủy có tính phí. Vui lòng kiểm
        tra một cách thấu đáo toàn bộ chi tiết (đặt phòng) của dịch vụ đã chọn về những điều kiện như vậy
        trước khi thực hiện đặt phòng. Lưu ý, một yêu cầu đặt phòng có yêu cầu đặt cọc hoặc thanh toán
        trước (toàn bộ hoặc một phần) sẽ có thể bị hủy (mà không cần báo trước hoặc cảnh báo trước) trong
        trường hợp các khoản tiền tương ứng còn lại không thể được thu đầy đủ vào ngày thanh toán đã định
        theo chính sách thanh toán được áp dụng bởi nhà cung cấp chỗ ở.
      </div>
      <div>
        <span class="title font-weight-bold">C</span>hính sách hủy và thanh tóan trước có thể thay đổi tùy theo phân khúc, sản phẩm hay dịch vụ của lần đặt phòng. Vui lòng đọc kỹ ghi chú
        (tại mỗi trang của nhà cung cấp chỗ ở) và các thông tin quan trọng trong xác nhận đặt chỗ của bạn về
        những chính sách bổ sung có thể được áp dụng bởi nhà cung cấp chỗ ở. Bạn sẽ chịu trách nhiệm và rủi
        ro về thanh toán trễ, nhầm lẫn ngân hàng, chi tiết thẻ tín dụng/ghi nợ hoặc không đủ số dư tài khoản
        và sẽ không được quyền nhận bất cứ tiền hoàn lại nào của phần đã thanh toán trước (không hoàn lại).
        Nếu bạn muốn xem lại, chỉnh sửa hoặc hủy đặt phòng, vui lòng làm theo hướng dẫn trong email xác nhận.
        Xin lưu ý rằng bạn có thể bị tính phí nếu hủy đặt chỗ theo chính sách hủy, thanh toán trước và vắng
        mặt của nhà cung cấp chỗ ở hoặc không có quyền được hoàn lại bất kỳ khoản tiền nào đã trả trước.
      </div>
      <div>
        <span class="title font-weight-bold">C</span>>húng tôi khuyên bạn nên đọc chính sách hủy, thanh toán trước hoặc vắng mặt của nhà cung cấp chỗ ở
        kỹ càng trước khi thực hiện đặt chỗ của mình và hãy nhớ luôn thực hiện các thanh toán sau đó đúng
        thời hạn như đã được yêu cầu cho đặt chỗ tương ứng. Nếu bạn trễ vào ngày nhận phòng/sử dụng dịch vụ
        hoặc đến vào ngày hôm sau, hãy thông báo (kịp thời/nhanh chóng) với nhà cung cấp chỗ ở để họ biết khi
        nào bạn tới, tránh hủy đặt phòng hoặc tính phí vắng mặt.
      </div>
    </v-flex>
    <v-flex shrink md3 class="pa-2 subheading">
      <div><span class="title font-weight-bold">H</span>oàn tiền:</div>
      <div>
        - Xin lưu ý, nếu rút ngắn thời gian lưu trú trong mùa cao điểm, trong thời gian hiệu suất sử dụng
        phòng cao, trong thời gian các hội nghị hoặc hội chợ thương mại, hoặc không hủy phòng trước ngày
        quy định sau khi đã xác nhận đặt phòng, bạn có thể sẽ phải chịu phí phạt do khách sạn quy định.
        Nếu phát sinh phí phạt, số tiền này sẽ được trừ vào khoản tiền hoàn trả hoặc điểm xu.
      </div>
      <div>
        - Email xác nhận của bạn sẽ ghi rõ chính xác hạn hủy phòng, nghĩa là thời hạn cuối cùng bạn phải hủy
        để tránh phải chịu bất kỳ phí hủy phòng nào. Thông thường, thời hạn hủy phòng là 14 ngày trước khi
        nhận phòng tại địa điểm. Phí phạt thường là giá phòng một đêm, trừ khi được nêu rõ trong email xác
        nhận.
      </div>
      <div>
        - Nhiều nhà cung cấp chỗ ở sẽ áp dụng phí phạt nếu thông báo trễ về việc rút ngắn thời gian lưu trú,
        thường là tương ứng với một đêm lưu trú. Vui lòng đọc kỹ chính sách.
      </div>
      <div>
        - Nếu lỗi hủy đặt phòng không phải ở bạn, yên tâm, hệ thống sẽ hỗ trợ bạn bằng việc thông báo lý do
        và đền bù vào điểm xu của bạn (mức cao nhất 40% tổng phí đặt phòng).
      </div>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  mounted() {
  }
};
</script>
