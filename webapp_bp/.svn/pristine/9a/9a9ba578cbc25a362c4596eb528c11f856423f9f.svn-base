/**
 * 
 */
package com.yonghui.webapp.bp.filter;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpServletResponseWrapper;

/**
 * <b>描述：</b>接口拦截器<br>
 * <b>日期：</b>2016年5月25日<br>
 * <b>作者：</b>rush<br>
 *
 */
public class ApiFilter implements Filter {
	

	@Override
	public void destroy() {
		// TODO 自动生成的方法存根
		
	}

	@Override
	public void doFilter(ServletRequest rq, ServletResponse rs,
			FilterChain chain) throws ServletException, IOException {
		
		HttpServletRequest request = (HttpServletRequest) rq;
		HttpServletResponse response = (HttpServletResponse) rs;
		
		String uri = request.getRequestURI();
		if (!uri.endsWith(".jsp")) {
			chain.doFilter(rq, rs);
			return;
		}
		String apiName = uri.substring(uri.lastIndexOf("/") + 1);
		apiName = apiName.substring(0, apiName.lastIndexOf("jsp") - 1);
		APIFilteResponse apiRs = new APIFilteResponse(response);
		chain.doFilter(rq, apiRs);
		apiRs.flush();
		String content = apiRs.getContent();
		System.out.println("api["+apiName+"] result["+content+"]");
	}
	

	@Override
	public void init(FilterConfig arg0) throws ServletException {
		// TODO 自动生成的方法存根
		
	}
	
	private final static class APIFilterServletStream extends ServletOutputStream {
		private ByteArrayOutputStream outputStream;
		private OutputStream source;
		private String content;
		
		public APIFilterServletStream(OutputStream source) throws IOException {
			outputStream = new ByteArrayOutputStream();
			this.source = source;
		}

		public void abort() throws IOException {
			byte [] bs = outputStream.toByteArray();
			content = new String(bs, "UTF-8");
			source.write(bs);
			source.flush();
			outputStream.reset();
		}

		public void write(byte[] buf) throws IOException {
			outputStream.write(buf);
		}

		public void write(byte[] buf, int off, int len) throws IOException {
			outputStream.write(buf, off, len);
		}

		public void write(int c) throws IOException {
			outputStream.write(c);
		}

		public void flush() throws IOException {
			outputStream.flush();
		}

		public void close() throws IOException {
			outputStream.close();
		}
		
		public String getContent() {
			return content;
		}
	}

	private final static class APIFilteResponse extends HttpServletResponseWrapper {
		private APIFilterServletStream wrappedOut;
		private PrintWriter wrappedWriter;

		public APIFilteResponse(HttpServletResponse response) throws IOException {
			super(response);
			wrappedOut = new APIFilterServletStream(response.getOutputStream());
		}

		public ServletOutputStream getOutputStream() throws IOException {
			return wrappedOut;
		}

		public PrintWriter getWriter() throws IOException {
			if (wrappedWriter == null) {
				wrappedWriter = new PrintWriter(new OutputStreamWriter(getOutputStream(), getCharacterEncoding()));
			}
			return wrappedWriter;
		}

		public void flush() throws IOException {
			if (wrappedWriter != null) {
				wrappedWriter.flush();
			}
			wrappedOut.abort();
		}
		
		public String getContent() {
			return wrappedOut.getContent();
		}
	}
}
