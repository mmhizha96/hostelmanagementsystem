import { TestBed } from '@angular/core/testing';

import { HostelpreferenceService } from './hostelpreference.service';

describe('HostelpreferenceService', () => {
  let service: HostelpreferenceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HostelpreferenceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
